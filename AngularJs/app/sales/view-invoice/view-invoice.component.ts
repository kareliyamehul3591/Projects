import { Component, OnInit } from '@angular/core';
import { NgxSpinnerService } from 'ngx-spinner';
import { HttpService } from 'src/app/http.service';
@Component({
  selector: 'app-view-invoice',
  templateUrl: './view-invoice.component.html',
  styleUrls: ['./view-invoice.component.css']
})
export class ViewInvoiceComponent implements OnInit {
  recentdata: any;
  eid: any;
  customer_address: any;
  customer_name: any;
  term_of_payment: any;
  invoice_date: any;
  due_date: any;
  order_type: any;
  remark: any;
  description: any;
  taxed: any;
  amount: any;
  id: string;
  constructor(public http:HttpService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.fetch();
  }

  //get invoice details
  fetch()
  {
    this.id=localStorage.getItem('id');
    this.spinner.show();//show the spinner
    this.http.get(`sales/view_invoice`,{id:this.id}).subscribe((res:any)=>{ //api for get invoice details
      console.log(res);
       this.recentdata=res.result[0];
       this.eid=this.recentdata.id,
       this.customer_name=this.recentdata.customer_name,
       this.customer_address=this.recentdata.customer_address,
       this.term_of_payment=this.recentdata.term_of_payment,
       this.invoice_date=this.recentdata.invoice_date,
       this.due_date=this.recentdata.due_date,
       this.order_type=this.recentdata.order_type,
       this.remark=this.recentdata.remark,
       this.description=this.recentdata.description,
       this.taxed=this.recentdata.taxed,
       this.amount=this.recentdata.amount,
       this.spinner.hide();
    })
  }

}
