import { Component, OnInit,ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, FormArray } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { element } from 'protractor';
import { ActivatedRoute } from '@angular/router';
import { MatDialog, MatTableDataSource, MatSort, MatPaginator } from '@angular/material';
import * as jspdf from 'jspdf';
import html2canvas from 'html2canvas';
@Component({
  selector: 'app-add-enquiry',
  templateUrl: './add-enquiry.component.html',
  styleUrls: ['./add-enquiry.component.css']
})
export class AddEnquiryComponent implements OnInit {
  invoiceForm: FormGroup; //form group
  id: string;
 // listData:MatTableDataSource<any>;

  logvalue: any;
  notevalue: any;
  officevalue: any;
  clientvalue: any;
  invoicevalue: any;
  recentvalue: any;
  idvalue: any;
  date_value: any;
  order_number: any;
  invoice_no: any;
  registerationvalue: any;
  invoice_date: any;
  customer_name: any;
  customer_address: any;
  total: any;
  order_no: any;
  log: string;


  constructor(private formBuilder: FormBuilder, public http: HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService,private _route: ActivatedRoute,public dialog: MatDialog) { }

    listData: MatTableDataSource<any>; //LISTING DATA IN TABLE
    displayedColumns: string[] = ['description','quantity','unit-price','gst','amount'];

    @ViewChild(MatSort, null) sort: MatSort; //USE FOR FILTER
    @ViewChild(MatPaginator, null) paginator: MatPaginator; //USE FOR PAGINATION

    
    ngOnInit() {
       //get id by params
     this._route.params.subscribe(params => {
      let id = params['id'];
      this.id=id
      });
      this.buildForm();
      this.fetchregisteration();
      this.fetchorder_no();
      this.fetchlogo();
      this.fetchnote();
      this.fetchbuss_add();
      this.fetchclient_add();
      this.fetchinvoice_details();
      
    }
  
    buildForm() {
      this.invoiceForm = this.formBuilder.group({
        total: [0],
        order_invoice_info: this.formBuilder.array([])
      });
    }

    fetchinvoice_details()
    {
      this.spinner.show();//show the spinner
      this.http.get('sales/get_invoice_order',{id:this.id}).subscribe((res:any)=>{ //API FOR VIEW logo details
        console.log('ss');  
      console.log(res);    
      this.order_no=res.result[0].order_no,
      this.id=res.result[0].id,
      this.invoice_date=res.result[0].invoice_date,
      this.customer_name=res.result[0].customer_name
      this.customer_address=res.result[0].customer_address
      this.total=res.result[0].total
      var invoicevalue=res.result;
      this.listData=new MatTableDataSource(invoicevalue); //SET VALUE INTO LIST FROM GET API
      console.log(this.listData)
      this.listData.sort=this.sort; //USE FOR FILTER VALUE 
      this.listData.paginator=this.paginator; //USE FOR PAGINATION
      this.spinner.hide(); //HIDE SPINNER
     })
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

   //fetch order no detilas
   fetchorder_no()
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

  //fetch client address detilas
  fetchclient_add()
  {
    this.spinner.show();//show the spinner
    this.http.get('invoice/viewregister',{id:1}).subscribe((res:any)=>{ //API FOR VIEW client address details
    console.log(res);    
    
      this.clientvalue=res.result[0].address
      console.log(this.clientvalue);
      this.spinner.hide(); //HIDE SPINNER
    })
  }

  // public ConvertPDFWithWholepage()
  // {
  //   var data=document.getElementById('contentToConvert');
  //   html2canvas(data).then(canvas=>{
      
  //     var allowTaint : true;
  //     var postion=0;
  //     let pdf = new jspdf('l', 'pt', "a4");
  //     var imgWidth = pdf.internal.pageSize.getWidth();
  //     var imgHeight = pdf.internal.pageSize.getHeight();
  //     const contentDataURL=canvas.toDataURL('image/png');
        
  //     pdf.addImage(contentDataURL,1,postion,imgWidth,imgHeight)
  //     pdf.save('File.pdf');
  //   });
   
  // }

  public ConvertPDFWithWholepage()
  {
    
    var data=document.getElementById('contentToConvert');
    html2canvas(data).then(canvas=>{
      var useCORS:true;
      var allowTaint : true;
      var postion=0;
      
      let pdf = new jspdf('l', 'pt', "a4");
      //var imageData
      
      var imgWidth = pdf.internal.pageSize.getWidth();
      var imgHeight = pdf.internal.pageSize.getHeight();
      const contentDataURL=canvas.toDataURL('image/png');
   
      pdf.addImage(contentDataURL,1,postion,imgWidth,imgHeight)
      pdf.save('File.pdf');
      window.open(contentDataURL)
    });
  }


  //html2canvas(document.getElementById('invoice-panel'), { letterRendering: 1, allowTaint : true, onrendered : function (canvas) { } });
}

