import { Component, OnInit,ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, FormArray } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { ActivatedRoute } from '@angular/router';
import { MatDialog, MatTableDataSource, MatSort, MatPaginator } from '@angular/material';
import html2canvas from 'html2canvas';
import * as jspdf from 'jspdf';
@Component({
  selector: 'app-view-purchase-order',
  templateUrl: './view-purchase-order.component.html',
  styleUrls: ['./view-purchase-order.component.css']
})
export class ViewPurchaseOrderComponent implements OnInit {
  registerationvalue: any;
  logvalue: any;
  notevalue: any;
  officevalue: any;
  log: string;
  id: any;
  events: any;
  material_name: any;
  sub_total: any;
  discount: any;
  recentvalue: any;
  delivery_date: any;
  vendor_name: any;
  
  constructor(private formBuilder: FormBuilder, public http: HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService,private _route: ActivatedRoute,public dialog: MatDialog) { }
    
    listData: MatTableDataSource<any>; //LISTING DATA IN TABLE
    displayedColumns: string[] = ['name-of-material', 'description', 'quantity', 'unit-rate', 'amount', 'action']; //FEILDS NAME SHOW IN TABLE
    
    @ViewChild(MatSort, null) sort: MatSort; //USE FOR FILTER
    @ViewChild(MatPaginator, null) paginator: MatPaginator; //USE FOR PAGINATION


  ngOnInit() {
    this._route.params.subscribe(params => {
      let id = params['id'];
      this.id=id
    
      console.log(this.id);
  });
    this.fetchregisteration();
    this.fetch_purchase_order_details();
    this.fetchlogo();
    this.fetchnote();
    this.fetchbuss_add();
    this.fetch_purchase_order();
  }

  //fetch registeration no detilas
  fetchregisteration()
  {
    this.spinner.show();//show the spinner
    this.http.get('invoice/registeration_view',{id:1}).subscribe((res:any)=>{ //API FOR VIEW logo details
    console.log(res);    
    
      this.registerationvalue=res.result[0].register
      console.log(this.registerationvalue);
      this.spinner.hide(); //HIDE SPINNER
    })
  }

  //fetch invoice setting detilas
  fetchlogo()
  {
    this.spinner.show();//show the spinner
    this.http.get('invoice/logo-view',{id:1}).subscribe((res:any)=>{ //API FOR VIEW logo details
    console.log(res);    
    
      this.logvalue=res.result[0].logo
      console.log(this.logvalue);
       this.log='http://localhost:4000/'+this.logvalue;
      console.log(this.log);
      this.spinner.hide(); //HIDE SPINNER
    })
  }

  //fetch important note detilas
  fetchnote()
  {
    this.spinner.show();//show the spinner
    this.http.get('invoice/viewnote',{id:1}).subscribe((res:any)=>{ //API FOR VIEW note details
    console.log(res);    
    
      this.notevalue=res.result[0].note
      console.log(this.notevalue);
      this.spinner.hide(); //HIDE SPINNER
    })
  }

  //fetch bisiness address detilas
  fetchbuss_add()
  {
    this.spinner.show();//show the spinner
    this.http.get('invoice/viewaddress',{id:1}).subscribe((res:any)=>{ //API FOR VIEW business address details
    console.log(res);    
    
      this.officevalue=res.result[0].address
      console.log(this.officevalue);
      this.spinner.hide(); //HIDE SPINNER
    })
  }
  
  fetch_purchase_order_details(){
    this.spinner.show();//show the spinner
    this.http.get('purchase/purchase_order_details',{id:this.id}).subscribe((res:any)=>{ //api for get emp details
        console.log(res);
        this.events = res.result;
        console.log('jk')
        console.log(this.events);
        
        this.spinner.hide(); //hide spinner
    })
}

fetch_purchase_order(){
  this.spinner.show();//show the spinner
  this.http.get('purchase/details_purchase_order_info',{id:this.id}).subscribe((res:any)=>{ //api for get emp details
    console.log('kk')
      console.log(res.result);
      this.delivery_date=res.result[0].delivery_date,
      this.vendor_name=res.result[0].vendor_name,
      this.recentvalue=res.result
  })
}

//use for convert htl to pdf
public ConvertPDFWithWholepage()
  {
    var data=document.getElementById('contentToConvert');
    html2canvas(data).then(canvas=>{
      var allowTaint : true;
      var postion=0;
      let pdf = new jspdf('l', 'pt', "a4");
      var imgWidth = pdf.internal.pageSize.getWidth();
      var imgHeight = pdf.internal.pageSize.getHeight();
      const contentDataURL=canvas.toDataURL('image/png');
      pdf.addImage(contentDataURL,1,postion,imgWidth,imgHeight)
      pdf.save('File.pdf');
    });
  }

}
