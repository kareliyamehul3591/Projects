import { Component, OnInit,ViewChild } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { MatSort } from '@angular/material/sort';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-show-emp-leave',
  templateUrl: './show-emp-leave.component.html',
  styleUrls: ['./show-emp-leave.component.css']
})
export class ShowEmpLeaveComponent implements OnInit {
  recentdata: any; //DECLARE
  recentdata1:any; //DECLARE
  id: any; //DECLARE
  yes: string; //DECLARE
  acpt: Boolean = true; //BOOLEAN VALUE FOR SHOW BUTTON
  rejt: Boolean = true; //BOOLEAN VALUE FOR HIDE BUTTON
  designation: any; //DECLARE

  constructor(public http:HttpService,private spinner: NgxSpinnerService) { }

  listData:MatTableDataSource<any>;  //LISTING DATA IN TABLE
  listData1:MatTableDataSource<any>; //LISTING DATA IN TABLE
    displayedColumns:string[]=['emp_code','first_name','desgn','type','from_date','to_date','total_days','reason','id']; //FEILDS NAME SHOW IN TABLE
    
    
    @ViewChild(MatSort,null) sort:MatSort; //USE FOR FILTER
    @ViewChild(MatPaginator,null) paginator:MatPaginator; //USE FOR PAGINATION

  ngOnInit() {
    this.fetch();
    this.fetch1();
    this.designation=localStorage.getItem('designation');
  }

  //UPDATE LEAVE DETAILS
  update(id)
  {
    if(confirm("Are you sure to reject leave?")){
    console.log(id);
    const data={
     status:'Rejected'
    }
    this.spinner.show();//show the spinner
        this.http.put(`leave/rejectLeave`,data,{id:id}).subscribe((res:any)=>{ //API FOR UPDATE LEAVES
        console.log(res);
        this.ngOnInit();
        this.spinner.hide();//HIDE SPINNER
    });
    }
  }

  //ACCEPT LEAVE
  accept(id,type,total_days,eid)
  {
    console.log(id);
    console.log(type);
    console.log(total_days);
    console.log(eid);
    if(confirm("Are you sure to accept leave?")){
      console.log('hi');
    const data={
      id:id,
      type:type,
      totalDays:total_days,
      eid:eid,
    } 
    console.log('hi');
    this.spinner.show();//show the spinner
    this.http.post('leave/accept',data).subscribe((res:any)=>{ //API FOR ACCEPT LEAVES
      console.log(res);
      if(res['message']=='Leave Accepted successfully')
      {
        this.acpt=true;
        this.rejt=false;
        this.ngOnInit();
      }
      this.spinner.hide();//HIDE SPINNER
    })
    }
  }

  //GET ALL LEAVE DETAILS OF EMPLOYEE
  fetch()
  {
    this.spinner.show();//show the spinner
    this.http.get('leave/LeaveAppliAlllook').subscribe((res:any)=>{ //API FOR GET LEAVE APLLICATION DETAILS FOR ADMIN PORTAL
      console.log(res);
      var recentdata=res.result;
      this.listData=new MatTableDataSource(recentdata); //SET VALUE INTO LIST FROM GET API
      console.log(this.listData)
      this.listData.sort=this.sort;  //USE FOR FILTER VALUE 
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

  //GET LEAVE DETAILS FOR HR
  fetch1()
  {
    this.spinner.show();//show the spinner
    this.http.get('leave/lookLeaveAppliAll').subscribe((res:any)=>{ //API FOR GET LEAVE APLLICATION DETAILS FOR HR PORTAL
      console.log('hi')
      console.log(res);
      var recentdata1=res.result;
      this.listData1=new MatTableDataSource(recentdata1); //SET VALUE INTO LIST FROM GET API
      console.log(this.listData1)
      this.listData1.sort=this.sort; //USE FOR FILTER VALUE 
      this.listData1.paginator=this.paginator; //USE FOR PAGINATION
      this.spinner.hide(); //HIDE SPINNER
    });
  }

  //FUNCTION FOR APPLY FILTER TO LIST VALUE
  applyFilter1(filtervalue:string){
    this.listData1.filter=filtervalue.trim().toLocaleLowerCase();
    if (this.listData1.paginator) {
      this.listData1.paginator.firstPage();
    }
  }

}
