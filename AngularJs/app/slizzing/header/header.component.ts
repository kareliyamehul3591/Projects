import { Component, OnInit } from '@angular/core';
import { HttpService } from 'src/app/http.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {

  constructor(private _authService:HttpService) { }

  ngOnInit() {
  }

}
