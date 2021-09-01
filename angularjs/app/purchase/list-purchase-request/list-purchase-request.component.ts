import { Component, OnInit, ViewChild } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { NgxSpinnerService } from 'ngx-spinner';
import { ToastrService } from 'ngx-toastr';
import { MatDialog, MatTableDataSource, MatSort, MatPaginator } from '@angular/material';
import { ViewPurchaseReqComponent } from '../view-purchase-req/view-purchase-req.component';
import { EditPurchaseReqComponent } from '../edit-purchase-req/edit-purchase-req.component';
import { Router } from '@angular/router';

@Component({
  selector: 'app-list-purchase-request',
  templateUrl: './list-purchase-request.component.html',
  styleUrls: ['./list-purchase-request.component.css']
})
export class ListPurchaseRequestComponent implements OnInit {

  constructor(public http: HttpService,public router:Router,private spinner: NgxSpinnerService, private toastr: ToastrService, public dialog: MatDialog) { }

  listData: MatTableDataSource<any>;
  displayedColumns: string[] = ['prf-no', 'date', 'request-generated-by','request-generated','request-approved','description','action'];

  @ViewChild(MatSort, null) sort: MatSort;  //USE FOR FILTER
  @ViewChild(MatPaginator, null) paginator: MatPaginator;  //USE FOR PAGINATION

  ngOnInit() {
    this.fetchpurchase_req();
  }

  //get purchase request value
  fetchpurchase_req()
  {
    this.spinner.show();//show the spinner
    this.http.get('sales/fetch_purchase_req').subscribe((res:any)=>{ //api for show lead details
      console.log(res);
      var recentvalue=res.result;
      this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
      console.log(this.listData)
      this.listData.sort=this.sort; //USE FOR FILTER VALUE 
      this.listData.paginator=this.paginator; //USE FOR PAGINATION
      this.spinner.hide();//hide the spinner
    })
  }

  //FUNCTION FOR APPLY FILTER TO LIST VALUE
  applyFilter(filtervalue:string){
    this.listData.filter=filtervalue.trim().toLocaleLowerCase();
    if (this.listData.paginator) {
      this.listData.paginator.firstPage();
    }
  }


//delete purchase req details
delete_purchase(id)
{
  if(confirm("Are You Sure To Delete Data ?")){
    this.spinner.show();//show the spinner
      this.http.delete(`sales/delete_purchase/${id}`).subscribe((res: any) => { //api for delete purchase req detail
      console.log(res);
      this.toastr.success('Data Deleted Successfully');
      this.ngOnInit()
      this.spinner.hide();
      });
   }
}

//view purchase request modal
openDialog(id) {
  console.log(id);
  localStorage.setItem('id',id);
  const dialogRef = this.dialog.open(ViewPurchaseReqComponent,{
    id:id,
    width: '50%',
    height:'80%',
    
  });

  dialogRef.afterClosed().subscribe(result => {
    
    console.log(`Dialog result: ${result}`);
  });
}

//update purchase request modal
openDialog1(id) {
  console.log(id);
  localStorage.setItem('id',id);
  const dialogRef = this.dialog.open(EditPurchaseReqComponent,{
    id:id,
    width: '50%',
    height:'80%',
    
  });

  dialogRef.afterClosed().subscribe(result => {
    
    console.log(`Dialog result: ${result}`);
  });
}

view_purchase(id){
this.router.navigate([`/view-purchase-request/${id}`])
}

edit_purchase(id)
{
  this.router.navigate([`/edit-purchase-request/${id}`])
}

}
