import { Component, OnInit } from '@angular/core';
import { AdminService } from 'src/app/services/admin.service';

@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.css']
})
export class AdminComponent implements OnInit {

  files: Array<{name: string, progress: number, status: string, badge: string}> = [];

  constructor(private adminService: AdminService) { }

  ngOnInit(): void {
  }

  uploadFile(event) {
    for (const element of event) {
      const file = { name: element.name, progress: 0, status: 'uploading', badge: 'primary' };
      this.adminService.uploadProductsCSV(element, element.name).subscribe((evt) => {
        console.log('progress', evt);
        if (evt.type === 1) {
          file.progress = (evt.loaded / evt.total) * 100;
        }
      }, (err) => {
        console.log('error upload', err);
        file.status = 'failed';
        file.badge = 'danger';
      }, () => {
        file.progress = 100;
        file.status = 'uploaded';
        file.badge = 'success';
      });
      this.files.push(file);
    }
  }

  deleteAttachment(index) {
    this.files.splice(index, 1);
  }

}
