import { Component, OnInit,ViewChild } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator'; 
import { MatSort } from '@angular/material/sort';
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { NgxSpinnerService } from 'ngx-spinner';
import { ToastrService } from 'ngx-toastr';
import { ViewleadComponent } from '../viewlead/viewlead.component';
import { EditOrderComponent } from '../edit-order/edit-order.component';
import { Router } from '@angular/router';
@Component({
  selector: 'app-list-order',
  templateUrl: './list-order.component.html',
  styleUrls: ['./list-order.component.css']
})
export class ListOrderComponent implements OnInit {

  constructor(public router:Router,public http:HttpService,private spinner: NgxSpinnerService,
    private toastr: ToastrService,public dialog: MatDialog) { }

  listData:MatTableDataSource<any>; //LISTING DATA IN TABLE
  displayedColumns:string[]=['order_number','order_name','order_type','amount','close_date',
                             'current_stage','lead_source','delivery_stage','description','id'];//FEILDS NAME SHOW IN TABLE

  @ViewChild(MatSort,null) sort:MatSort; //USE FOR FILTER
  @ViewChild(MatPaginator,null) paginator:MatPaginator; //USE FOR PAGINATION

  ngOnInit() {
    this.fetch();
  }

  //get order details
  fetch()
  {
    this.spinner.show();//show the spinner
    this.http.get('sales/fetchorder').subscribe((res:any)=>{ //api for show order details
     console.log(res);
     var recentvalue=res.result;
     this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
     console.log(this.listData)
     this.listData.sort=this.sort; //USE FOR FILTER VALUE 
     this.listData.paginator=this.paginator; //USE FOR PAGINATION
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

  //delete order details
  delete_order(id)
  {
    if(confirm("Are You Sure To Delete Data ?")){
      this.spinner.show();//show the spinner
        this.http.delete(`sales/deleteorder/${id}`).subscribe((res: any) => { //api for delete order
        console.log(res);
        this.toastr.success('Data Deleted Successfully');
        this.ngOnInit()
        this.spinner.hide();//hide spinner
        });
     }
  }

  //edit order list
  edit_order(id)
  {
    this.router.navigate([`/edit-order/${id}`])
  }

}
