import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient, HttpParams, HttpRequest, HttpHeaders } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class AdminService {

  constructor(private http: HttpClient) { }

  uploadProductsCSV(file, description: string): Observable<any> {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('description', description);
    const params = new HttpParams();
    const accessToken = localStorage.getItem('access_token');
    const headers = new HttpHeaders({ Authorization: 'Bearer ' + accessToken });
    const options = {
      params,
      reportProgress: true,
      headers,
    };
    const req = new HttpRequest('POST', 'http://localhost:8000/api/admin/products/bulk', formData, options);
    return this.http.request(req);
  }
}
