import { Component, OnInit,ViewChild } from '@angular/core';
import { DatePipe } from '@angular/common';
import {formatDate,Location } from '@angular/common';
import { HttpService } from 'src/app/http.service';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { ToastrService } from 'ngx-toastr'; //use for show toast
import { Router } from '@angular/router';//user for route on another page
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { NgxSpinnerService } from "ngx-spinner"; //user for show spinner
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator'; 
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { MatSort } from '@angular/material/sort';
@Component({
  selector: 'app-add-attendance',
  templateUrl: './add-attendance.component.html',
  styleUrls: ['./add-attendance.component.css']
})
export class AddAttendanceComponent implements OnInit {
  registerform: FormGroup; //FormGroup
  show: Boolean = true; //Function for show button
  hide: Boolean = false; //Function for hide button
  now:number;
  today: number = Date.now(); //display current date
  jstoday = '';
  date='';
  getvalue: any; //declare value
  first_name: any; //declare first name
  last_name: any;  //declare last name
  username: string; //declare user name
  emp_id: string; //declare emp id
  today1= new Date();

  constructor(public http:HttpService,private formBuilder: FormBuilder,public dialog: MatDialog,public toastr:ToastrService,
    public router:Router,public _location:Location,private spinner: NgxSpinnerService) 
  {
    //display current date time year
    setInterval(() => {
      this.now = Date.now();
      this.jstoday = formatDate(this.today1, 'yyyy-MM-dd hh:mm:ss', 'en-US', '+0530');
    }, 1
    );

    //display current date
    setInterval(() => {this.today = Date.now();
      this.date = formatDate(this.now, 'yyyy-MM-dd', 'en-US', '+0530');
    }, 1);
  }

  listData:MatTableDataSource<any>; //LISTING DATA IN TABLE
    displayedColumns:string[]=['date','check_in','check_out','activity','attendance','worked_hours']; //FEILDS NAME SHOW IN TABLE

  @ViewChild(MatSort,null) sort:MatSort; //USE FOR FILTER
  @ViewChild(MatPaginator,null) paginator:MatPaginator; //USE FOR PAGINATION

  ngOnInit() {
    this.fetch();//show emp details
    this.getvalue=[];
    this.first_name="";
    this.last_name="";
    this.ShowAttend();//show attendance details of logined employee//

    //validate form's field
    this.registerform=this.formBuilder.group({
      activity:['',Validators.required]
    })
    this.first_name=localStorage.getItem('first_name');
    this.last_name=localStorage.getItem('last_name');
  }
 
  //verify is employee checkin already or not
  showchekin()
  {
    this.spinner.show();//show the spinner
    this.http.get('leave/checkattend').subscribe((res:any)=>{ //api for add check attendance
      console.log(res);
      if(res['message']=='already')
      {
        this.toastr.warning('You already apply attendance!', 'Warning!');
      }
      else
      {
        const data={
          checkIn:this.jstoday,
          date:this.date,
        }
        this.spinner.hide();//show the spinner

        this.spinner.show();//show the spinner
        this.http.post('leave/addAttend',data).subscribe((res:any)=>{ //api for add attendance
          console.log(res);
          if(res['message']=='Post successfully')
          {
            this.hide=true;
            this.show=false;
            this.toastr.success('You check in successfully!', 'Success!');
          }
          this.spinner.hide();//show the spinner
        })
      }
    })
  }
 
  //checkout function
  showchekout()
  {
    this.emp_id=localStorage.getItem('emp_id');
      var date=(this.date);
      const data={
        activity:this.registerform.value.activity,
        checkOut:this.jstoday,
        emp_id:this.emp_id
      }
    this.spinner.show();//show the spinner
    this.http.put('leave/updateAttend',data,{date:date}).subscribe((res:any)=>{ //api for checkout
     console.log(res);
     this.hide=false;
     this.show=true;
      if(res['message']=='Updated successfully')
      {
        this.toastr.success('You check out successfully!', 'Success!');
        this.http.get('leave/ShowAttend',{emp_id:this.emp_id}).subscribe((res:any)=>{
          console.log(res);
          this.getvalue=res.result;
        })
      }
    this.spinner.hide();//hide the spinner
    })
  }

  //get all attendance details of emp
  fetch()
  {
    this.spinner.show();//show the spinner
    this.emp_id=localStorage.getItem('emp_id');
     console.log(this.emp_id);
    this.http.get('leave/fetchvalue').subscribe((res:any)=>{ //api for get attendance details
      console.log(res);
      var recentvalue=res.result;
      this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
      console.log(this.listData)
      this.listData.sort=this.sort; //USE FOR FILTER VALUE 
      this.listData.paginator=this.paginator; //USE FOR PAGINATION 
      this.spinner.hide(); //HIDE SPINNER
      if(res['message']=='already exist')
      {
        this.hide=true;
        this.show=false;
      }
      else if(res['message']=='not')
      {
        this.hide=false;
        this.show=true;
      }
    })
  }

  //FUNCTION FOR APPLY FILTER TO LIST VALUE
  applyFilter(filtervalue:string){
    this.listData.filter=filtervalue.trim().toLocaleLowerCase();
      if (this.listData.paginator) {
        this.listData.paginator.firstPage();
      }
  }

  //show attendance details of logined employee
  ShowAttend()
  {
    
    this.username=localStorage.getItem('username');
     console.log(this.username)
    this.http.get('leave/ShowAttend').subscribe((res:any)=>{ //api for show attendance details
      console.log(res);
      this.getvalue=res.result;
    })
  }
}
