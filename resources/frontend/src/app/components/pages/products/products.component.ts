import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Params, Router } from '@angular/router';
import { ProductsService } from 'src/app/services/products.service';
import { Product } from 'src/app/models/product';
import { PaginatedResult } from 'src/app/models/PaginatedResult';

@Component({
  selector: 'app-products',
  templateUrl: './products.component.html',
  styleUrls: ['./products.component.css']
})
export class ProductsComponent implements OnInit {
  query: string;
  page: number;
  products: PaginatedResult<Product>;
  loading = true;

  constructor(private activatedRoute: ActivatedRoute, private productsService: ProductsService, public router: Router) { }

  ngOnInit(): void {
    this.activatedRoute.paramMap.subscribe((params) => {
      this.query = '';
      if (params.has('q')) {
        this.query = params.get('q');
      }
      if (params.has('page')) {
        const page = Number(params.get('page'));
        if (isNaN(page)) {
          this.page = 1;
        } else {
          this.page = page;
        }
      }
      this.loading = true;
      this.search();
    });
  }

  search(): void {
    this.productsService.search(this.query, null, this.page, 9).subscribe((res) => {
      this.products = res;
      this.loading = false;
    });
  }

  pageChange(page: number): void {
    this.router.navigate(['search', { q: this.query, page }]);
  }
}
