import { Component, OnInit,ViewChild } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { ViewGenSalaryComponent } from '../view-gen-salary/view-gen-salary.component';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { NgxSpinnerService } from "ngx-spinner";
import { MatSort } from '@angular/material/sort';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator';
@Component({
  selector: 'app-salary-history',
  templateUrl: './salary-history.component.html',
  styleUrls: ['./salary-history.component.css']
})
export class SalaryHistoryComponent implements OnInit {
  recentdata: any; //declare
  getvalue:any; //declare
  month:any; //declare
  year:any; //declare

  constructor(public http:HttpService,public dialog: MatDialog,public toastr:ToastrService,private router:Router,
    private _route: ActivatedRoute,private spinner: NgxSpinnerService) { }

    listData:MatTableDataSource<any>; //LISTING DATA IN TABLE
    displayedColumns:string[]=['month_name','year','count','totalemp','pdf']; //FEILDS NAME SHOW IN TABLE
    
    
    @ViewChild(MatSort,null) sort:MatSort; //USE FOR FILTER
    @ViewChild(MatPaginator,null) paginator:MatPaginator; //USE FOR PAGINATION


  ngOnInit() {
    this.http.get('salary/showsalhistory').subscribe((res:any)=>{ //api for show salary history
      console.log(res);
      this.recentdata=res.result;
    });
    this.showsalaryslip();
  }

  salarypdf(month,year) 
  {
   console.log(month);
  this.router.navigate(['/view-gen-salary',{month,year}])
  }

  //delete salary history
 delete_emp(eid,month_num,year)
 {
  this.spinner.show();//show the spinner
  this.http.delete(`salary/DeleteSal/${eid}`).subscribe((res:any)=>{ //api for delete salary history
  console.log(res);
  this.toastr.success('Data Deleted Successfully');
  this.ngOnInit()
  this.spinner.hide();//hide the spinner
  })
}

showsalaryslip()
{
  this.spinner.show();//show the spinner
  this.http.get(`salary/looksalary_slip`).subscribe((res:any)=>{ //api for show all salary histoy details of employee
    console.log(res);
   var recentvalue=res.result;
   this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
   console.log(this.listData)
   this.listData.sort=this.sort;  //USE FOR FILTER VALUE 
   this.listData.paginator=this.paginator; //USE FOR PAGINATION 
   this.spinner.hide();//show the spinner
  })
}

//FUNCTION FOR APPLY FILTER TO LIST VALUE
applyFilter(filtervalue:string){
  this.listData.filter=filtervalue.trim().toLocaleLowerCase();
  if (this.listData.paginator) {
    this.listData.paginator.firstPage();
  }
}

}
