import { Component, OnInit } from '@angular/core';
import { ViewEmpComponent } from 'src/app/employee/view-emp/view-emp.component';
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { ViewSalaryComponent } from '../view-salary/view-salary.component';
import { EditSalaryComponent } from '../edit-salary/edit-salary.component';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from "ngx-spinner";
import { Router } from '@angular/router';
import * as XLSX from 'xlsx';
@Component({
  selector: 'app-show-salary',
  templateUrl: './show-salary.component.html',
  styleUrls: ['./show-salary.component.css']
})
export class ShowSalaryComponent implements OnInit {
  recentdata: any; //declare

  constructor(public dialog: MatDialog,public http:HttpService,
    public toastr:ToastrService,private spinner: NgxSpinnerService,public router:Router) { }

  ngOnInit() {
    this.spinner.show();//show the spinner
    this.http.get(`salary/showSalaryDetailsAll`).subscribe((res:any)=>{ //api for show salary details
      console.log(res);
      this.recentdata=res.result;
      this.spinner.hide();//hide spinner
    })
  }

  //view salary modal
  openDialog(sid) {
    console.log(sid);
    localStorage.setItem('id',sid);
    const dialogRef = this.dialog.open(ViewSalaryComponent,{
      width: '50%',
      height:'80%',
    });
    dialogRef.afterClosed().subscribe(result => {
    console.log(`Dialog result: ${result}`);
    });
  }

  //edit salary modal
  editsalary(sid)
  {
    console.log(sid);
    this.router.navigate([`/edit-salary/${sid}`])
  }

  //delete salary details
  delete_salary(sid)
  {
    console.log(sid);
    if(confirm("Are You Sure To Delete Data ?")){
        this.spinner.show();//show spinner
        this.http.delete(`salary/${sid}`).subscribe((res: any) => { //apii for delete salary details
            console.log(res);
            this.toastr.success('Data Deleted Successfully');
            this.ngOnInit()
            this.spinner.hide();//hide spinner
        });
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
      this.http.post('salary/add_excel_fitment',jsonObj).subscribe((res:any)=>{ //API FOR ADD EMPLOYEE DETAILS
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
