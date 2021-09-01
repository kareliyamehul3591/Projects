import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { HttpService } from 'src/app/http.service';
@Component({
  selector: 'app-create-order',
  templateUrl: './create-order.component.html',
  styleUrls: ['./create-order.component.css']
})
export class CreateOrderComponent implements OnInit {
  registerform: FormGroup;
  recentvalue: any;
  mailing_address: any;
  mailing_street: any;
  city: any;
  country: any;
  state: any;
  zip_code: any;
  customer_address: any;
  inactive: number;
  milestonevalue:any;
  constructor(private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.inactive=1;
    console.log(this.inactive)
    this.buildForm();
    this.getcustomer();
  }

  //VALIDATOR FOR FORM'S FIELD
  buildForm(){
    this.registerform=this.formBuilder.group({
      order_name:['',[Validators.required]],
      order_number:[''],
      order_type:[''],
      amount:[],
      close_date:[],
      current_stage:[''],
      milestone_date:[''],
      lead_source:[''],
      delivery_stage:[''],
      description:[]
      
    });  
  }

 
  //add order details
  onsubmit():void
  {
    console.log(this.registerform.value)
    const data={
      orderName:this.registerform.value.order_name,
      orderNumber:this.registerform.value.order_number,
      orderType:this.registerform.value.order_type,
      amount:this.registerform.value.amount,
      closeDate:this.registerform.value.close_date,
      currentStage:this.registerform.value.current_stage,
      milestoneDate:this.registerform.value.milestone_date,
      leadSource:this.registerform.value.lead_source,
      deliveryStage:this.registerform.value.delivery_stage,
      description:this.registerform.value.description,
      customerAddress:this.customer_address
     }
    this.spinner.show();//show the spinner
    this.http.post('sales/addorder',data).subscribe((res:any)=>{ //api for add order details
      console.log(res);
      if(res['message']=='Post successfully')
      {
        console.log('save');
        this.toastr.success('Data save successfully!', 'SUCCESS!');
        this.registerform.reset();
      }
      else{
       this.toastr.error('Something wrong!', 'Error!');
       console.log('not');
      }
      this.spinner.hide();//hide spinner
    })
  }

  //get customer details
  getcustomer()
  {
    this.spinner.show();//show the spinner
    this.http.get('sales/getcustomer').subscribe((res:any)=>{ //api for show customer details
      console.log(res);
     this.recentvalue=res.result;
     console.log(this.recentvalue)
      this.spinner.hide(); //hide spinner
    })
  }

  //get milestone details against order type
  getmilestone(value)
  {
    this.spinner.show();//show the spinner
    this.http.get('sales/get_milestone_det',{id:value}).subscribe((res:any)=>{ //api for show milestone details
      console.log(res);
     this.milestonevalue=res.result;
     console.log(this.milestonevalue)
      this.spinner.hide(); //hide spinner
    })
  }

  getcustomer_address(value)
  {
    console.log(value)
    this.spinner.show();//show the spinner
    this.http.get('sales/customer_addres_view',{id:value}).subscribe((res:any)=>{ //api for show customer details
      console.log(res);
     this.mailing_address=res.result[0].mailing_address;
     this.mailing_street=res.result[0].mailing_street;
     this.city=res.result[0].city;
     this.state=res.result[0].state;
     this.zip_code=res.result[0].zip_code;
     this.country=res.result[0].country;
     this.customer_address=this.mailing_address +' ' +this.mailing_street + ' '+this.city + ' ' +this.state+' '+this.zip_code+' '+this.country;
     console.log('hhu');
     console.log(this.customer_address)
      this.spinner.hide(); //hide spinner
    })
  }
}
