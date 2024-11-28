import { Component, OnInit,ViewChild} from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from "ngx-spinner";
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator'; 
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { MatSort } from '@angular/material/sort';
@Component({
  selector: 'app-create-form16',
  templateUrl: './create-form16.component.html',
  styleUrls: ['./create-form16.component.css']
})
export class CreateForm16Component implements OnInit {
  registerform: FormGroup; //form group
  recentdata: any; //declare
  fname: any; //declare
  lname:any; //declare
  months;
  years = []; //declare
  getdata: any; //declare

  constructor(private formBuilder: FormBuilder,public http:HttpService,
    private toastr: ToastrService,private spinner: NgxSpinnerService,public dialog: MatDialog) { }

   formData=new FormData();

   listData:MatTableDataSource<any>; //LISTING DATA IN TABLE
    displayedColumns:string[]=['emp_code','first_name','desgn','form16_pdf_path']; //FEILDS NAME SHOW IN TABLE
    
    @ViewChild(MatSort,null) sort:MatSort; //USE FOR FILTER
    @ViewChild(MatPaginator,null) paginator:MatPaginator; //USE FOR PAGINATION
    
  ngOnInit() {
    this.fetch();
    this.getDates();
    this.showdata();
    // validators for form field
    this.registerform=this.formBuilder.group({
        emp_id:['',Validators.required],
        year:['',Validators.required],
        form16_pdf_path:['',Validators.required],
    })
  }

  //get all employee details
 fetch()
  {
    this.spinner.show();//show the spinner
    this.http.get('emp/allEmp').subscribe((res:any)=>{ //api for get all employee details
      console.log(res);
      this.recentdata=res.result;
      this.spinner.hide();//hide spinner
    })
  }

  
  selectOption(id)
  {
        console.log(id);
        this.spinner.show();//show the spinner
        this.http.get('emp/vieEmpName',{id:id}).subscribe((res:any)=>{ //api for get employee name against id
        console.log(res);
        this.fname=res.result[0].first_name;
        this.lname=res.result[0].last_name;
        this.spinner.hide();
    })
  }

  //show year
  getDates() {
    var date = new Date();
    var currentYear = date.getFullYear();
    console.log(date);
    for (var i = 0; i <= 5; i++) {
      this.years.push(currentYear - i);
        console.log(this.years[0])
    }
    this.months = [{name:"Jan",id:1}, {name:"Feb",id:2},{name:"Mar",id:3},{name:"Apr",id:4},
                   {name:"May",id:5},{name:"Jun",id:6},{name:"Jul",id:7},{name:"Aug",id:8},
                   {name:"Sep",id:9},{name:"Oct",id:10},{name:"Nov",id:11},{name:"Dec",id:12}]
    console.log(this.months)
 }

  onSelectedFile2(event)
  {
    console.log(event.target.files);
    if(event.target.files){
    const formData=new FormData();
    this.formData.append('form_16', event.target.files[0]);
    }
  }

  onSubmit():void
  {
    console.log(this.registerform.value);
    this.formData.append('emp_id',this.registerform.get('emp_id').value);
    this.formData.append('year',this.registerform.get('year').value);
    this.spinner.show();//show the spinner
    this.http.post('salary/form16add',this.formData).subscribe((res:any)=>{ //api for add form16 of employee
        console.log('hi')
        console.log(res);
    if(res['message']=='Post successfully')
    {
      this.toastr.success('Data Save Successfully','Success!');
      this.registerform.reset();
      this.formData = new FormData();
      this.fname = '';
      this.lname = '';
      this.ngOnInit();
    }
    this.spinner.hide(); //hide spinner
    }, err=>{
      this.toastr.error(err.message || err);
    })
  }

  //show form16
  showdata()
 { 
  this.spinner.show();//show the spinner
  this.http.get('leave/formshow16').subscribe((res:any)=>{ //api for show selected form 16
    console.log(res);
    var recentvalue=res.result;
    this.listData=new MatTableDataSource(recentvalue);
    console.log(this.listData)
    this.listData.sort=this.sort;
    this.listData.paginator=this.paginator;
    this.spinner.hide();
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
