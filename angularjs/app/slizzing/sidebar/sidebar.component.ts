import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { HttpService } from 'src/app/http.service';

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.css']
})
export class SidebarComponent implements OnInit {
  designation: string;
  spinnerService: any;
  getvalue: any;
  first_name: any;
  last_name: any;

  constructor(public router:Router,public http:HttpService) { }

  ngOnInit() {
    //this.addatt();
    this.first_name=localStorage.getItem('first_name');
    this.last_name=localStorage.getItem('last_name');
    this.designation=localStorage.getItem('designation');
    
  }

  addatt(): void {
    // window.location.reload();
    this.router.navigate(["/attendance"]);
    //this.ngOnInit();
  }
 

}
