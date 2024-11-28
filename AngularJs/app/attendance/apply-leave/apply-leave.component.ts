import { Component, OnInit,ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from "ngx-spinner";
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator'; 
import { MatSort } from '@angular/material/sort';

@Component({
  selector: 'app-apply-leave',
  templateUrl: './apply-leave.component.html',
  styleUrls: ['./apply-leave.component.css']
})
export class ApplyLeaveComponent implements OnInit {
  registerform: FormGroup; //FORM GROUP
  showleave: any; //DECLARE
  recentdata: any; //DECLARE
  username: string; //DECLARE
  emp_id: string; //DECLARE
  
  constructor(private formBuilder: FormBuilder,public dialog: MatDialog,public http:HttpService,private toastr: ToastrService,
    private spinner: NgxSpinnerService) { }

    listData:MatTableDataSource<any>; //LISTING DATA IN TABLE
    displayedColumns:string[]=['from_date','to_date','total_days','reason','type','status']; //FEILDS NAME SHOW IN TABLE
    
    
    @ViewChild(MatSort,null) sort:MatSort; //USE FOR FILTER
    @ViewChild(MatPaginator,null) paginator:MatPaginator; //USE FOR PAGINATION
 

  ngOnInit() {
   this.fetchleave();
   this.emp_id=localStorage.getItem('emp_id');
   this.spinner.show();//show the spinner
    this.http.get(`leave/showleave`,{emp_id:this.emp_id}).subscribe((res:any)=>{  //API FOR GET LEAVE DETAILS
      console.log(res);
      
      var recentvalue=res.result;
      this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
      console.log(this.listData)
      this.listData.sort=this.sort; //USE FOR FILTER VALUE 
      this.listData.paginator=this.paginator; //USE FOR PAGINATION 
      this.spinner.hide(); //HIDE SPINNER
    });

    //VALIDATORS FOR FORM'S FIELDS
    this.registerform=this.formBuilder.group({
      from_date:['',Validators.required],
      to_date:['',Validators.required],
      type:['',Validators.required],
      reason:['',Validators.required],
    })
  }

  //FUNCTION FOR APPLY FILTER TO LIST VALUE
  applyFilter(filtervalue:string){
    this.listData.filter=filtervalue.trim().toLocaleLowerCase();
      if (this.listData.paginator) {
        this.listData.paginator.firstPage();
      }
  }
  
  //ONSUBMIT FUNCTION
  onSubmit():void
  {
    const data={
      fromDate:this.registerform.value.from_date,
      toDate:this.registerform.value.to_date,
      type:this.registerform.value.type,
      reason:this.registerform.value.reason,
    }
    console.log(this.registerform.value);
    this.spinner.show();//show the spinner
    this.http.post('leave/addleave',data).subscribe((res:any)=>{ //API FOR ADD APPLIED LEAVE
      console.log(res);
      if(res['message']=='Post successfully')
       {
         console.log('save');
         this.toastr.success('Data save successfully!', 'SUCCESS!');
         this.registerform.reset();
         this.ngOnInit();
       }
       else{
        this.toastr.error('Something Wrong!', 'Error!');
        console.log('not');
       }
       this.spinner.hide();
    })
  }

  //SHOW LEAVE DETAILS
  fetchleave()
  {
    this.username=localStorage.getItem('username');
     console.log(this.username)
    this.http.get('leave/viewLeaveDetails',{username:this.username}).subscribe((res:any)=>{ //API FOR SHOW LEAVE DETAILS
     console.log(res);
    this.recentdata=res.result;
   })
  }
}
