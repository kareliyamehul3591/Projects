import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, FormArray } from '@angular/forms';
import { MatTableDataSource } from '@angular/material';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { ActivatedRoute } from '@angular/router';
import * as jspdf from 'jspdf';
import html2canvas from 'html2canvas';

@Component({
  selector: 'app-view-purchase-req',
  templateUrl: './view-purchase-req.component.html',
  styleUrls: ['./view-purchase-req.component.css']
})
export class ViewPurchaseReqComponent implements OnInit {
  //purchaseRequestForm: FormGroup; //form group

  listData: MatTableDataSource<any>;
  displayedColumns: string[] = ['description', 'vendor-name', 'quantity', 'unit_price', 'gst', 'amount'];
  getemp: any;
  id: any;
  recentvalue: any;
    notevalue: any;
    officevalue: any;
    logvalue: any;
    registerationvalue: any;
    recentvalue1: any;
    date: any;
    request_approved: any;
    request_generated_by: any;
  request_generated: any;
  officename: any;
  total: any;
  constructor(private formBuilder: FormBuilder, public http: HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService,private _route: ActivatedRoute) { }

  ngOnInit() {
    this._route.params.subscribe(params => {
      let id = params['id'];
      this.id=id
      console.log(this.id);
      });
      this.get_purchase_req_details();
      this.fetchbuss_add();
      this.fetchnote();
      this.fetchlogo();
      this.fetchregisteration();
      this.get_purchase_req_info();
  }

get_purchase_req_details()
{
 this.http.get('purchase/get_purchase_info',{id:this.id}).subscribe((res:any)=>{ //API FOR ADD INVOIE
    console.log(res.result);
     this.total=res.result[0].total;
     console.log(this.total);
    this.recentvalue=res.result;
  })
}

get_purchase_req_info()
{
 this.http.get('purchase/view_purchase_details',{id:this.id}).subscribe((res:any)=>{ //API FOR ADD INVOIE
  console.log(res);
    this.date=res.result[0].date,
    this.request_approved=res.result[0].request_approved,
    this.request_generated_by=res.result[0].request_generated_by,
    this.request_generated=res.result[0].request_generated
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

 //fetch invoice setting detilas
 fetchlogo()
 {
   this.spinner.show();//show the spinner
   this.http.get('invoice/logo-view',{id:1}).subscribe((res:any)=>{ //API FOR VIEW logo details
   console.log(res);    
   
     this.logvalue=res.result[0].logo
     console.log(this.logvalue);
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
     this.officename=res.result[0].name,
      this.officevalue=res.result[0].address
      console.log(this.officevalue);
      this.spinner.hide(); //HIDE SPINNER
    })
  }
  
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
