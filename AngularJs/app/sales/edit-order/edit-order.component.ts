import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { HttpService } from 'src/app/http.service';
import { Router } from '@angular/router';
import { ActivatedRoute } from '@angular/router';
@Component({
  selector: 'app-edit-order',
  templateUrl: './edit-order.component.html',
  styleUrls: ['./edit-order.component.css']
})
export class EditOrderComponent implements OnInit {
  id: any;
  registerform: FormGroup;
  recentvalue: any;
  order_number: any;
  constructor(private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService,public router:Router,private _route: ActivatedRoute) { }

  ngOnInit() {
     //get id by params
     this._route.params.subscribe(params => {
      let id = params['id'];
      this.id=id
      console.log(this.id);
      });
    this.buildForm();
    this.fetch();
    this.getcustomer();
  }

  //VALIDATOR FOR FORM'S FIELD
  buildForm(){
    this.registerform=this.formBuilder.group({
      order_name:['',[Validators.required]],
      order_number:[''],
      order_type:[''],
      amount:[''],
      close_date:[''],
      current_stage:[''],
      milestone_date:[''],
      lead_source:[''],
      delivery_stage:[''],
      description:[''],
    });  
  }

  //get order details
  fetch()
  {
    console.log(this.id);
    this.spinner.show();//show the spinner
    this.http.get('sales/vieworder',{id:this.id}).subscribe((res:any)=>{ //api for get order details
     console.log(res);
     this.order_number=res.result[0].order_number;
     this.recentvalue=res.result[0].milestone_date
      this.registerform.patchValue({
        order_name: res.result[0].order_name,
        order_number: res.result[0].order_number,
        order_type: res.result[0].order_type,
        amount: res.result[0].amount,
        close_date: res.result[0].close_date,
        current_stage: res.result[0].current_stage,
        milestone_date: this.recentvalue,
        lead_source: res.result[0].lead_source,
        delivery_stage: res.result[0].delivery_stage,
        description: res.result[0].description,
       })
      this.spinner.hide();//hide the spinner
   });
  }

  //edit order details
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
      leadSource:this.registerform.value.lead_source,
      deliveryStage:this.registerform.value.delivery_stage,
      description:this.registerform.value.description
    }
    this.spinner.show();//show the spinner
    this.http.put('sales/updateOrder',data,{id:this.id}).subscribe((res:any)=>{ //api for edit ordre details
      console.log(res);
      if(res['message']=='Updated successfully')
      {
        this.toastr.success('Data Updated successfully!', 'SUCCESS!');
      }
      this.spinner.hide();//show the spinner
    })
  }

  //get customer details
  getcustomer()
  {
    this.spinner.show();//show the spinner
    this.http.get('sales/getcustomer').subscribe((res:any)=>{ //api for show customer details
      console.log(res);
      this.recentvalue=res.result;
      this.spinner.hide();//hide the spinner
    })
  }

  //
  addorder()
  {
    console.log(this.id)
    // let id=this.order_number;
    this.router.navigate([`/add-order-invoice/${this.id}`]);
    // this.router.navigate([`/add-order-invoice/${order_number}`])
  }

}
