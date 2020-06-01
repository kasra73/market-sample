import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Observable } from 'rxjs';
import { switchMap } from 'rxjs/operators';
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
  products: PaginatedResult<Product>;

  constructor(private route: ActivatedRoute, private productsService: ProductsService) { }

  ngOnInit(): void {
    this.route.paramMap.subscribe((params) => {
      this.query = '';
      if (params.has('q')) {
        this.query = params.get('q');
      }
      this.search();
    });
  }

  search(): void {
    this.productsService.search(this.query, null, 1, 9).subscribe((res) => {
      this.products = res;
    });
  }

}
