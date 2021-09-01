import { Component, OnInit } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-form-sixteen',
  templateUrl: './form-sixteen.component.html',
  styleUrls: ['./form-sixteen.component.css']
})
export class FormSixteenComponent implements OnInit {
  value: any; //declare
  path: any;  //declare
  recent: any; //declare
    
  constructor(public http:HttpService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.fetchvalue();
  }

  selectvalue(value)
  {
    console.log(value);
    this.value=value
    this.http.get('leave/getform16',{year:this.value}).subscribe((res:any)=>{ //api for get form 16
      console.log(res);
      this.path=res.result[0].form16_pdf_path;
      console.log(this.path);
      
    })
  }

  fetchvalue()
  {
    this.spinner.show();//show the spinner
    this.http.get('leave/yearform16').subscribe((res:any)=>{ 
      console.log(res);
      this.recent=res.result;
      console.log(this.recent);
      this.spinner.hide();//hide spinner
    })
  }

}
