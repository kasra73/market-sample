<?php

namespace App\Jobs;

use App\File as FileModel;
use App\Product;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/** @todo Add result field to uploaded file */
class HandleFileUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var FileModel */
    private $uploadedFile;

    /** @var string */
    private $result;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(FileModel $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
        $this->result = '';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ignore_errors = $this->uploadedFile->ignore_invalid_rows;
        $content = Storage::get('uploads/' . $this->uploadedFile->filename);
        DB::beginTransaction();
        try {
            $row_number = 0;
            /** @var Product[] */
            $products = [];
            $inserted_rows_count = 0;
            foreach (preg_split("/((\r?\n)|(\r\n?))/", $content) as $line) {
                $row_number = $row_number + 1;
                if ($row_number === 1) {
                    $title_row = str_getcsv($line);
                    continue;
                }
                $product = $this->handleLine($line, $title_row, $ignore_errors, $row_number);
                if ($product !== null) {
                    $products[] = $product;
                    $inserted_rows_count += 1;
                }
            }
            $this->result .= "$inserted_rows_count items inserted.\n";
            $this->uploadedFile->status = 'success';
            $this->uploadedFile->result = $this->result;
            $this->uploadedFile->save();
            DB::commit();
            // Manually fire index job because of a known issue: https://github.com/laravel/framework/issues/8627
            foreach ($products as $product) {
                dispatch(new IndexProductJob($product));
            }
        } catch(\Exception $e) {
            Log::error('Error handling file', ['error' => $e->getMessage()]);
            $this->uploadedFile->status = 'failed';
            $this->uploadedFile->result = $this->result;
            $this->uploadedFile->save();
        }
    }

    private function handleLine(string $line, array $title_row, $ignore_errors, $row_number): ?Product
    {
        $row = str_getcsv($line);
        if (count($row) > 1) {
            $productData = array_combine($title_row, $row);
            try {
                return $this->insertProduct($productData, $ignore_errors);
            } catch (\Exception $e) {
                $this->result .= "Cannot insert product on row $row_number. Error: " . $e->getMessage() . "\n";
                Log::error('Error inserting product', [
                    'product' =>  $productData,
                    'error' => $e->getMessage()
                ]);
                if (!$ignore_errors) {
                    DB::rollback();
                    throw new Exception('Failed to handle file');
                }
                $this->result .= "Ignores error as ignore_errors flag is true\n";
            }
        }
        return null;
    }

    /**
     * insert product record
     */
    private function insertProduct(array $productData, bool $ignore_errors = false): ?Product
    {
        Log::debug('inserting product', ['data' => $productData]);
        if ($this->validateProduct($productData)) {
            $product = new Product($productData);
            $product->save();
            return $product;
        }
        return null;
    }

    /**
     * Validate product
     *
     * @return bool
     */
    private function validateProduct(array $input): bool
    {
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'min:1', 'max:200'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'numeric'],
            'amount' => ['required', 'integer', 'min:0'],
            'description' => ['string', 'nullable']
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $this->result .= "Error validating product info:\n";
            Log::error('Invalid product info', ['errors' => $errors]);
            foreach($errors as $error) {
                $this->result .= $error . "\n";
            }
            throw new ValidationException($validator);
        }
        return true;
    }
}
