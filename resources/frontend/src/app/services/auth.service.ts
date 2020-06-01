import { tap, map, startWith } from 'rxjs/operators';
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { User } from '../models/user';
import * as moment from 'moment';
import { Observable, Subscriber, Subject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private user: Subject<User> = null;

  constructor(private http: HttpClient) {
    this.user = new Subject();
    if (this.isLoggedIn()) {
      this.getUserInfo();
    }
  }

  userInfo(): Observable<User> {
    return this.user;
  }

  login(email: string, password: string) {
    return this.http.post('http://localhost:8000/api/auth/login', { email, password })
      .pipe(
        tap(res => this.setSession(res)),
        tap((res) => this.getUserInfo())
      );
  }

  logout() {
    localStorage.removeItem('access_token');
    localStorage.removeItem('expires_at');
    this.user.next(null);
  }

  isLoggedIn(): boolean {
    return moment().isBefore(this.getExpiration());
  }

  isLoggedOut(): boolean {
    return !this.isLoggedIn();
  }

  getExpiration() {
    const expiration = localStorage.getItem('expires_at');
    const expiresAt = JSON.parse(expiration);
    return moment(expiresAt);
  }

  private setSession(authResult) {
    const expiresAt = moment().add(authResult.expires_in, 'second');

    localStorage.setItem('access_token', authResult.access_token);
    localStorage.setItem('expires_at', JSON.stringify(expiresAt.valueOf()));
  }

  private getUserInfo() {
    const accessToken = localStorage.getItem('access_token');
    this.http.get<User>('http://localhost:8000/api/user', {
      headers: {
        Authorization: 'Bearer ' + accessToken,
      }
    }).subscribe((user) => {
      this.user.next(user);
    });
  }
}
