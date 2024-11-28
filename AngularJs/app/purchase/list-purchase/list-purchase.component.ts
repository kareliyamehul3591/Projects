import { Component, OnInit, ViewChild } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { NgxSpinnerService } from 'ngx-spinner';
import { ToastrService } from 'ngx-toastr';
import { MatDialog, MatTableDataSource, MatSort, MatPaginator } from '@angular/material';
import { Router } from '@angular/router';

@Component({
  selector: 'app-list-purchase',
  templateUrl: './list-purchase.component.html',
  styleUrls: ['./list-purchase.component.css']
})
export class ListPurchaseComponent implements OnInit {

  constructor(public http: HttpService,public router:Router, private spinner: NgxSpinnerService, private toastr: ToastrService, public dialog: MatDialog) { }

  listData: MatTableDataSource<any>;
  displayedColumns: string[] = ['vendor-name', 'term-of-payment', 'delivery-date', 'amount','action'];

  @ViewChild(MatSort, null) sort: MatSort; //USE FOR FILTER
  @ViewChild(MatPaginator, null) paginator: MatPaginator; //USE FOR PAGINATION

  ngOnInit() {
    this.fetchpurchase_order();
  }

  //get lead details
  fetchpurchase_order()
  {
    this.spinner.show();//show the spinner
    this.http.get('purchase/fetch_purchase_order_info').subscribe((res:any)=>{ //api for show lead details
      console.log(res);
      var recentvalue=res.result;
      this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
      console.log(this.listData)
      this.listData.sort=this.sort; //USE FOR FILTER VALUE 
      this.listData.paginator=this.paginator; //USE FOR PAGINATION
      this.spinner.hide();//hide the spinner
    })
  }
  
  //redirect btn for edit order 
  edit_purchase_order(id){
    this.router.navigate([`/edit-purchase-order/${id}`])
  }

  //delete purchase req details
  delete_purchase_order(id)
  {
    if(confirm("Are You Sure To Delete Data ?")){
      this.spinner.show();//show the spinner
        this.http.delete(`purchase/delete_purchase_order/${id}`).subscribe((res: any) => { //api for delete purchase order detail
        console.log(res);
        this.toastr.success('Data Deleted Successfully');
        this.ngOnInit()
        this.spinner.hide();
        });
    }
  }

  //redirect btn for view purchase
  view_purchase(id)
  {
   this.router.navigate([`/view-purchase-order/${id}`])
  }
  
}
