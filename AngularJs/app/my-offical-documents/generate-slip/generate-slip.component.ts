import { Component, OnInit, ViewChild,ElementRef } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { Router, ActivatedRoute } from '@angular/router';
import { ViewGenSalaryComponent } from '../view-gen-salary/view-gen-salary.component';
import { ToastrService } from 'ngx-toastr';
import { UpdategensalaryComponent } from '../updategensalary/updategensalary.component';
import * as XLSX from 'xlsx'; 
//import { UpdateGenSalaryComponent } from '../update-gen-salary/update-gen-salary.component';
@Component({
  selector: 'app-generate-slip',
  templateUrl: './generate-slip.component.html',
  styleUrls: ['./generate-slip.component.css']
})
export class GenerateSlipComponent implements OnInit {
  registerform: FormGroup; //form group
  months;
  years = []; //declare
  currentyr: any; //declare
  recentdata:any; //declare
  selectedAll:any; //declare
  recent:any; //declare
  month:any; //declare
  year:any; //declare
  
  constructor(private formBuilder: FormBuilder,public toastr:ToastrService,
    public dialog: MatDialog,public http:HttpService,private router:Router, private _route: ActivatedRoute) { }

    @ViewChild('TABLE', { static: false }) TABLE: ElementRef;  

    ngOnInit() {
        const month_name=this._route.snapshot.paramMap.get('month')
        const year_name=this._route.snapshot.paramMap.get('year')
        console.log('j')
        this.month=month_name;
        this.year=year_name;
        this.fetchsalaryslip();
        this.fetch();
        this.getDates();
        //validators for form's feild
        this.registerform=this.formBuilder.group({
            monthName:['',Validators.required],
            year:['',Validators.required]
        })
    }

    fetchsalaryslip()
    {
        const data={
        month:this.month,
        year:this.year
        }
        this.http.post('salary/getsalslip',data).subscribe((res:any)=>{ //api for get salary slip of employee
            console.log(res);
            this.recentdata=res.result;
        });
    }
    //function for get year , date value
    getDates() {
        var date = new Date();
        var currentYear = date.getFullYear();
        for (var i = 0; i <= 5; i++) {
        this.years.push(currentYear - i);
        }
        this.months = [{name:"Jan",id:1}, {name:"Feb",id:2},{name:"Mar",id:3},{name:"Apr",id:4},
                        {name:"May",id:5},{name:"Jun",id:6},{name:"Jul",id:7},{name:"Aug",id:8},
                        {name:"Sep",id:9},{name:"Oct",id:10},{name:"Nov",id:11},{name:"Dec",id:12}]
        console.log(this.months)
    }

    onSubmit():void
    {
        this.month=this.registerform.value.monthName,
        this.year=this.registerform.value.year
        const data={
            month:this.registerform.value.monthName,
            year:this.registerform.value.year
        }
        this.http.post('salary/CreateSalaryslip',data).subscribe((res:any)=>{ //api for generate salary slip
        console.log(res);
        if(res['message']=='Post successfully')
        {
            this.toastr.success('Data Updated successfully!', 'SUCCESS!');
            this.ngOnInit();
        }
        else if(res['message']=='data already exists')
        {
            this.toastr.warning('Duplicate Entry!', 'Warning!');
        }
        })
    }

  //salary generayte view modal
  openDialog(eid) {
    console.log(eid);
    localStorage.setItem('id',eid);
    const dialogRef = this.dialog.open(ViewGenSalaryComponent,{
     width: '50%',
     height:'80%',
    });
    dialogRef.afterClosed().subscribe(result => {
    console.log(`Dialog result: ${result}`);
   });
 }

 //salary generayte update modal
 openDialog1(eid) {
  console.log(eid);
  localStorage.setItem('id',eid);
  const dialogRef = this.dialog.open(UpdategensalaryComponent,{
   width: '50%',
   height:'80%',
  });
  dialogRef.afterClosed().subscribe(result => {
  console.log(`Dialog result: ${result}`);
 });
}

selectAll() {
  for (var i = 0; i < this.recentdata.length; i++) {
    this.recentdata[i].selected = this.selectedAll;
    console.log(this.recentdata[i].selected);
  }
}

checkIfAllSelected(eid,checked) {
  console.log(eid);
  console.log(checked);
 if(checked==true)
 {
   console.log('h');
 }
 else{
  console.log('b');
 }
  this.selectedAll = this.recentdata.every(function(item:any) {
    return item.selected == true;
  })
}

//delete employee
delete_emp(eid,month_num,year)
{
  console.log(eid);
  console.log(month_num);
  console.log(year);
  this.http.delete(`salary/DeleteSal/${eid}`).subscribe((res:any)=>{ //api for delete employee salary details
    console.log(res);
    this.toastr.success('Data Deleted Successfully');
    this.ngOnInit()
  })
}

send()
{
    let data=[];
    this.recentdata.forEach(x => {
    if(x.isSelected) {
        data.push(x.eid);
    }
    })
}

//convert to excel file function
ExportTOExcel() {  
  const ws: XLSX.WorkSheet = XLSX.utils.table_to_sheet(this.TABLE.nativeElement);  
  const wb: XLSX.WorkBook = XLSX.utils.book_new();  
  XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');  
  XLSX.writeFile(wb, 'ScoreSheet.xlsx');  
}  

fetch()
{
  this.http.get('salary/showSalaryDetailsAll').subscribe((res:any)=>{ //show salary details
    console.log(res);
    this.recent=res.result;
  })
}

}  


