import { Component, OnInit,ViewChild } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator'; 
import { MatSort } from '@angular/material/sort';
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { NgxSpinnerService } from 'ngx-spinner';
import { ToastrService } from 'ngx-toastr';
import { ViewVendorComponent } from '../view-vendor/view-vendor.component';
import { EditVendorComponent } from '../edit-vendor/edit-vendor.component';
import { Router } from '@angular/router';

@Component({
  selector: 'app-list-vendor',
  templateUrl: './list-vendor.component.html',
  styleUrls: ['./list-vendor.component.css']
})
export class ListVendorComponent implements OnInit {

  constructor(public router:Router,public http:HttpService,private spinner: NgxSpinnerService,private toastr: ToastrService,public dialog: MatDialog) { }

  listData:MatTableDataSource<any>;  //LISTING DATA IN TABLE
  displayedColumns:string[]=['vendor_name','vendor_address','contact_person','contact_number','vendor_code','gst','id'];//FEILDS NAME SHOW IN TABLE

  @ViewChild(MatSort,null) sort:MatSort; //USE FOR FILTER
  @ViewChild(MatPaginator,null) paginator:MatPaginator; //USE FOR PAGINATION
  ngOnInit() {
    this.fetch();
  }

  //get vendor detaiils
  fetch()
  {
    this.http.get('sales/fetchvendor').subscribe((res:any)=>{ //api for show vendor details
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

  //delete vendor details
  delete_vendor(id)
  {
    if(confirm("Are You Sure To Delete Data ?")){
      this.spinner.show();//show the spinner
        this.http.delete(`sales/deletevendor/${id}`).subscribe((res: any) => {//api for delete vendor
        console.log(res);
        this.toastr.success('Data Deleted Successfully');
        this.ngOnInit()
        this.spinner.hide();//hide spinner
        });
     }
  }

  //view vendor modal
  openDialog(id) {
    console.log(id);
    localStorage.setItem('id',id);
    const dialogRef = this.dialog.open(ViewVendorComponent,{
      id:id,
      width: '50%',
      height:'80%',
      
    });

    dialogRef.afterClosed().subscribe(result => {
      
      console.log(`Dialog result: ${result}`);
    });
  }

  //edit vendor
  edit_vendor(id)
  {
    this.router.navigate([`/edit-vendor/${id}`])
  }
}
