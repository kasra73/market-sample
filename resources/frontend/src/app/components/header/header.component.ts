import { Component, OnInit } from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';
import { User } from 'src/app/models/user';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {

  public user: User = null;
  public searchQuery: string;

  constructor(public router: Router, private authService: AuthService) {
    this.authService.userInfo().subscribe((user) => {
      this.user = user;
    });
  }

  ngOnInit(): void {
    this.router.events.subscribe((val) => {
      if (val instanceof NavigationEnd) {
        this.searchQuery = '';
      }
    });
  }

  search(): void {
    this.router.navigate(['search', { q: this.searchQuery }]);
  }

  logout(): void {
    this.authService.logout();
  }
}
