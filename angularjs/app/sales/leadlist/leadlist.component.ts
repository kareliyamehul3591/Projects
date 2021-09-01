import { Component, OnInit,ViewChild } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator'; 
import { MatSort } from '@angular/material/sort';
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { NgxSpinnerService } from 'ngx-spinner';
import { ToastrService } from 'ngx-toastr';
import { ViewleadComponent } from '../viewlead/viewlead.component';
import { EditleadComponent } from '../editlead/editlead.component';
import { Router } from '@angular/router';
@Component({
  selector: 'app-leadlist',
  templateUrl: './leadlist.component.html',
  styleUrls: ['./leadlist.component.css']
})
export class LeadlistComponent implements OnInit {
 

  constructor(public router:Router,public http:HttpService,private spinner: NgxSpinnerService,
    private toastr: ToastrService,public dialog: MatDialog) { }

  listData:MatTableDataSource<any>; //LISTING DATA IN TABLE
  displayedColumns:string[]=['id','fname','email','mobile_no','company','follow_up','lead_status','eid'];//FEILDS NAME SHOW IN TABLE

  @ViewChild(MatSort,null) sort:MatSort; //USE FOR FILTER
  @ViewChild(MatPaginator,null) paginator:MatPaginator; //USE FOR PAGINATION

  ngOnInit() {
    this.fetchvalue();
  }

  //get lead value
  fetchvalue()
  {
    this.http.get('sales/fetchlead').subscribe((res:any)=>{ //api for show lead details
      console.log(res);
      var recentvalue=res.result;
      this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
      console.log(this.listData)
      this.listData.sort=this.sort; //USE FOR FILTER VALUE 
      this.listData.paginator=this.paginator; //USE FOR PAGINATION
    })
  }

  //FUNCTION FOR APPLY FILTER TO LIST VALUE
  applyFilter(filtervalue:string){
    this.listData.filter=filtervalue.trim().toLocaleLowerCase();
    if (this.listData.paginator) {
      this.listData.paginator.firstPage();
    }
  }

  status(value)
  {
    console.log(value);
    if(value==0)
    {
      this.fetchvalue();
    }
    else{
      this.http.get('sales/getstatus',{status:value}).subscribe((res:any)=>{//api for show status
        console.log(res);
        var recentvalue1=res.result;
        this.listData=new MatTableDataSource(recentvalue1);
        console.log(this.listData)
        this.listData.sort=this.sort;
        this.listData.paginator=this.paginator;
      })
    }
  }

  //delete lead details
  delete_emp(id)
  {
    if(confirm("Are You Sure To Delete Data ?")){
      this.spinner.show();//show the spinner
        this.http.delete(`sales/deletelead/${id}`).subscribe((res: any) => { //api for delete lead detail
        console.log(res);
        this.toastr.success('Data Deleted Successfully');
        this.ngOnInit()
        this.spinner.hide();
        });
     }
  }


  //view lead modal
  openDialog(id) {
    console.log(id);
    localStorage.setItem('id',id);
    const dialogRef = this.dialog.open(ViewleadComponent,{
      id:id,
      width: '50%',
      height:'80%',
      
    });

    dialogRef.afterClosed().subscribe(result => {
      
      console.log(`Dialog result: ${result}`);
    });
  }

  //edit lead
  edit_lead(id)
  {
    this.router.navigate([`/edit-lead-info/${id}`])
  }

}
