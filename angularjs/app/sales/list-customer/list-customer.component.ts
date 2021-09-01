import { Component, OnInit, ViewChild } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { NgxSpinnerService } from 'ngx-spinner';
import { ToastrService } from 'ngx-toastr';
import { MatDialog, MatTableDataSource, MatSort, MatPaginator } from '@angular/material';
import { ViewCustomerComponent } from '../view-customer/view-customer.component';
import { EditCustomerComponent } from '../edit-customer/edit-customer.component';
import { Router } from '@angular/router';

@Component({
  selector: 'app-list-customer',
  templateUrl: './list-customer.component.html',
  styleUrls: ['./list-customer.component.css']
})
export class ListCustomerComponent implements OnInit {

  constructor(public http: HttpService,public router:Router, private spinner: NgxSpinnerService,
    private toastr: ToastrService, public dialog: MatDialog) { }

  listData: MatTableDataSource<any>; //LISTING DATA IN TABLE

  //FEILDS NAME SHOW IN TABLE
  displayedColumns: string[] = ['customer-name', 'contact-numbers', 'email-address', 'website','birth-date','address-details','description','action'];

  @ViewChild(MatSort, null) sort: MatSort; //USE FOR FILTER
  @ViewChild(MatPaginator, null) paginator: MatPaginator; //USE FOR PAGINATION

  ngOnInit() {
    this.fetch();
  }

  //get customer details
  fetch()
  {
   this.spinner.show();//show the spinner
   this.http.get('sales/fetchcustomer').subscribe((res:any)=>{ //api for show customer
     console.log(res);
     var recentvalue=res.result;
     this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
     console.log(this.listData)
     this.listData.sort=this.sort; //USE FOR FILTER VALUE 
     this.listData.paginator=this.paginator; //USE FOR PAGINATION
     this.spinner.hide(); //hide spinner
   })
  }

  //FUNCTION FOR APPLY FILTER TO LIST VALUE
  applyFilter(filtervalue:string){
    this.listData.filter=filtervalue.trim().toLocaleLowerCase();
    if (this.listData.paginator) {
      this.listData.paginator.firstPage();
    }
  }

   //delete custmer details
  delete_customer(id)
  {
    if(confirm("Are You Sure To Delete Data ?")){
      this.spinner.show();//show the spinner
        this.http.delete(`sales/deletecustomer/${id}`).subscribe((res: any) => { //api for delete customer details
        console.log(res);
        this.toastr.success('Data Deleted Successfully');
        this.ngOnInit()
        this.spinner.hide();
        });
     }
  }

  //view customer modal
  openDialog(id) {
    console.log(id);
    localStorage.setItem('id',id);
    const dialogRef = this.dialog.open(ViewCustomerComponent,{
      id:id,
      width: '50%',
      height:'80%',
      
    });

    dialogRef.afterClosed().subscribe(result => {
      console.log(`Dialog result: ${result}`);
    });
  }
  
  //edit customer
  edit_customer(id)
  {
    this.router.navigate([`/edit-customer/${id}`]);
  }

 
}
