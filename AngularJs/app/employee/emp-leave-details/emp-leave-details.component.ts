import { Component, OnInit,ViewChild } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator'; 
import { MatSort } from '@angular/material/sort';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-emp-leave-details',
  templateUrl: './emp-leave-details.component.html',
  styleUrls: ['./emp-leave-details.component.css']
})

export class EmpLeaveDetailsComponent implements OnInit {
  recentdata: any; //DECLARE
  constructor(public http:HttpService,private spinner: NgxSpinnerService) { }

  listData:MatTableDataSource<any>;  //LISTING DATA IN TABLE
  displayedColumns:string[]=['emp_code','first_name','desgn','sl_taken','cl_taken','total-taken','sl_balance',
                            'cl_balance','total-balance']; //FEILDS NAME SHOW IN TABLE

  @ViewChild(MatSort,null) sort:MatSort; //USE FOR FILTER
  @ViewChild(MatPaginator,null) paginator:MatPaginator; //USE FOR PAGINATION

  ngOnInit() {
    this.fetch();
  }

  //GET LEAVE DETAILS OF ALL EMPLOYEES
  fetch()
  {
    this.spinner.show();//show the spinner
    this.http.get('leave/catchLeaveDetailsAll').subscribe((res:any)=>{ //API FOR GET ALL EMPLOYEE'S LEAVE DETAILS
      console.log(res);
      var recentvalue=res.result;
      this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
      console.log(this.listData)
      this.listData.sort=this.sort; //USE FOR FILTER VALUE 
      this.listData.paginator=this.paginator; //USE FOR PAGINATION 
      this.spinner.hide(); //HIDE SPINNER
    });
  }

  //FUNCTION FOR APPLY FILTER TO LIST VALUE
  applyFilter(filtervalue:string){
    this.listData.filter=filtervalue.trim().toLocaleLowerCase();
    if (this.listData.paginator) {
      this.listData.paginator.firstPage();
    }
  }
}
