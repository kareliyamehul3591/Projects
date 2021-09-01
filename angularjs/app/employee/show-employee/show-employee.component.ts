import { Component, OnInit,ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator'; 
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { ViewEmpComponent } from '../view-emp/view-emp.component';
import { EditEmployeeComponent } from '../edit-employee/edit-employee.component';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { MatSort } from '@angular/material/sort';
import { NgxSpinnerService } from "ngx-spinner";
import * as XLSX from 'xlsx';
@Component({
  selector: 'app-show-employee',
  templateUrl: './show-employee.component.html',
  styleUrls: ['./show-employee.component.css']
})
export class ShowEmployeeComponent implements OnInit {
  showemp: any; //DECLARE
  designation: any; //DECLARE
  willDownload = false;
  value:[];
  constructor(public router:Router,public dialog: MatDialog,public http:HttpService,
    public toastr:ToastrService,private spinner: NgxSpinnerService) { }

    listData:MatTableDataSource<any>; //LISTING DATA IN TABLE
    displayedColumns:string[]=['first_name','mobile_no','personal_email','desname','id']; //FEILDS NAME SHOW IN TABLE
    
    @ViewChild(MatSort,null) sort:MatSort; //USE FOR FILTER
    @ViewChild(MatPaginator,null) paginator:MatPaginator; //USE FOR PAGINATION

  ngOnInit() {
    this.fetch();  //GET ALL EMPLOYEE DETAILS
  }

  //DISPLAY VIEW MODALS
  openDialog(id) {
    console.log(id);
    localStorage.setItem('id',id);
    const dialogRef = this.dialog.open(ViewEmpComponent,{
      width: '50%',
      height:'80%',
    });
    dialogRef.afterClosed().subscribe(result => {
      console.log(`Dialog result: ${result}`);
    });
  }

  // //DISPLAY EDIT MODALS
  // openDialog1(id)
  // {
  //   localStorage.setItem('id',id);
  //   const dialogRef = this.dialog.open(EditEmployeeComponent,{
  //     width: '50%',
  //     height:'80%',
  //   });
  //   dialogRef.afterClosed().subscribe(result => {
  //     console.log(`Dialog result: ${result}`);
  //   });
  // }

  edit_employee(id)
  {
    console.log('hii')
    console.log(id)
    this.router.navigate([`/edit-emp/${id}`]);
    
  }

  //DELETE EMPLOYEE DETAILS AGAINST ID
   delete_emp(id)
  { 
    console.log(id);
    if(confirm("Are You Sure To Delete Data ?")){
        this.spinner.show();//show the spinner
        this.http.delete(`emp/${id}`).subscribe((res: any) => { //API FOR DELETE EMPLOYEE
            console.log(res);
            this.toastr.success('Data Deleted Successfully');
            this.ngOnInit()
            this.spinner.hide();
        });
    }
  }

  //GET ALL EMPLOYEE DETAILS
  fetch()
  {
    this.spinner.show();//show the spinner
    this.http.get('emp/Empallview').subscribe((res:any)=>{ //API FOR SHOW ALL EMPLOYEE DETAILS
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

  onFileChange(ev) {
    let workBook = null;
    let jsonData = null;
    const reader = new FileReader();
    const file = ev.target.files[0];
    reader.onload = (event) => {
      const data = reader.result;
      workBook = XLSX.read(data, { type: 'binary' });
      jsonData = workBook.SheetNames.reduce((initial, name) => {
        const sheet = workBook.Sheets[name];
        initial = XLSX.utils.sheet_to_json(sheet);
        return initial;
      }, {});
      const dataString = JSON.stringify(jsonData);
      //document.getElementById('output').innerHTML = dataString.slice(0, 300).concat("...");
      const value = dataString.slice(0, 300).concat("...");
     
      var jsonObj = JSON.parse(dataString);
      console.log(jsonObj)
      this.http.post('emp/add_excel_emp',jsonObj).subscribe((res:any)=>{ //API FOR ADD EMPLOYEE DETAILS
        console.log(res);
         if(res['message']=='Post successfully')
         {
             console.log('save');
             this.toastr.success('Upload successfully!', 'SUCCESS!');
             this.ngOnInit();
         }
         else{
          this.toastr.error('Something wrong!', 'Error!');
          console.log('not');
      }
        }, err=>{
          this.toastr.error('Something wrong!', 'Error!');
        }
        );
    }
    reader.readAsBinaryString(file);
  }

 

}
