import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Product } from '../models/product';
import { PaginatedResult } from '../models/PaginatedResult';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class ProductsService {

  constructor(private http: HttpClient) { }

  search(
    query: string = '',
    category: number = null,
    page: number = 1,
    perPage: number = 10): Observable<PaginatedResult<Product>> {
    const params: any = {
      query,
      page: page.toString(),
      per_page: perPage.toString()
    };
    if (category !== null) {
      params.category = category.toString();
    }
    return this.http.get<{ products: PaginatedResult<Product> }>(
      '/api/products/search', { params }
    ).pipe(
      map((result) => {
        return result.products;
      })
    );
  }
}
