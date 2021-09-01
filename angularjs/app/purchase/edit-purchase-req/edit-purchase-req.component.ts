import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, FormArray } from '@angular/forms';
import { MatTableDataSource } from '@angular/material';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { element } from 'protractor';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-edit-purchase-req',
  templateUrl: './edit-purchase-req.component.html',
  styleUrls: ['./edit-purchase-req.component.css']
})

export class EditPurchaseReqComponent implements OnInit {
    purchaseRequestForm: FormGroup; //form group
    listData: MatTableDataSource<any>;
    displayedColumns: string[] = ['description', 'vendor-name', 'quantity', 'unit_price', 'gst', 'amount', 'action'];
    getemp: any;
    id: any;
    events: any;
    total: any;
    cal: string;
    getvendor: any;
    
    constructor(private formBuilder: FormBuilder, public http: HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService,private _route: ActivatedRoute) { }

    ngOnInit() {
        this._route.params.subscribe(params => {
            let id = params['id'];
            this.id=id
            console.log(this.id);
        });
        this.buildForm();
        this.getemp_name();
        this.fetch_purchase();
        this.fetch_purchase_req_info();
        this.get_vendor();
    }

    buildForm() {
        this.purchaseRequestForm = this.formBuilder.group({
            date: [''],
            request_generated_by: [''],
            request_generated: [''],
            request_approved: [''],
            description: [''],
            total: [0],
            purchase_request_info: this.formBuilder.array([])
        });
    }

    fetch_purchase(){
        this.spinner.show();//show the spinner
        this.http.get('purchase/view_purchase_details',{id:this.id}).subscribe((res:any)=>{ //api for get emp details
            console.log(res);
            // this.getemp=res.result;
            this.total=res.result[0].total
            this.purchaseRequestForm.patchValue({
                date: res.result[0].date,
                request_generated_by: res.result[0].request_generated_by,
                request_generated:res.result[0].request_generated,
                request_approved:res.result[0].request_approved,
                description:res.result[0].description,
                total:res.result[0].total,
            })
            this.spinner.hide(); //hide spinner
        })
    }

    fetch_purchase_req_info(){
        this.spinner.show();//show the spinner
        this.http.get('purchase/get_purchase_info',{id:this.id}).subscribe((res:any)=>{ //api for get emp details
            console.log(res);
            this.events = res.result;
            console.log(this.events)
            for(let event of this.events){
                this.addPurchaseRequest(event)
            }
            this.spinner.hide(); //hide spinner
        })
    }

    // create or added purchase request info form
    createPurchaseRequestInfoForm(event){
        var event;
        console.log(event);
        console.log(event);
        if (!event) {
            event = this.formBuilder.group({
            purchase_description: [''],
            vendor_name:[''],
            quantity: [0],
            unit_price: [0],
            gst: [0],
            amount: [0],
        });
        } else {
            event = this.formBuilder.group({
                purchase_description: [event.purchase_description],
                vendor_name: [event.vendor_name],
                quantity: [event.quantity],
                unit_price: [event.unit_price],
                gst: [event.gst],
                amount: [event.amount],
            });
        }
        return event;
    }

    // add purchase request info
    addPurchaseRequest(event): void {
        const control = <FormArray>this.purchaseRequestForm.get('purchase_request_info');
        control.push(this.createPurchaseRequestInfoForm(event));
    }

    // remove pucahse request info
    public removeSection(i){
        const control = <FormArray>this.purchaseRequestForm.get('purchase_request_info');
        control.removeAt(i);
    }

    //purchase request info form array
    getSections(purchaseRequestForm) {
        return purchaseRequestForm.controls.purchase_request_info.controls;
    }

    calculateAmountAndTotal(index,value) {
        if (Number(value) > 0) {
            let total1=0
            let amount1 = 0
            const control = <FormArray>this.purchaseRequestForm.get('purchase_request_info');
            console.log(control)
            let quantity = Number(control.value[index]['quantity']);
            let unitPrice = Number(control.value[index]['unit_price']);
            let gstValue = Number(control.value[index]['gst']);

            //Quantity*Actual Rate)+GST%
            amount1 =((quantity * unitPrice) + (gstValue))
            this.purchaseRequestForm['controls']['purchase_request_info']['controls'][index]
            ['controls'].amount.patchValue(amount1);
            
            //calculate total
            this.getTotal();
        }
    }

    getTotal() {
        const control = <FormArray>this.purchaseRequestForm.get('purchase_request_info');
        //console.log(control)
        this.cal='1';
        let total = 0;
        this.purchaseRequestForm['controls']['purchase_request_info']['controls'].forEach(element => {
            total += (element.value.amount)*1;
        })
        this.purchaseRequestForm.patchValue({
            total:total
        })
        this.purchaseRequestForm.updateValueAndValidity();
    }

    getemp_name()
    {
        this.spinner.show();//show the spinner
        this.http.get('emp/Empallview').subscribe((res:any)=>{ //api for get emp details
        console.log(res);
            this.getemp=res.result;
            this.spinner.hide(); //hide spinner
        })
    }

    //get vendor name
    get_vendor()
    {
        //this.spinner.show();//show the spinner
        this.http.get('sales/fetchvendor').subscribe((res:any)=>{ //api for get vendor name details
            console.log(res);
            this.getvendor=res.result;
            //this.spinner.hide(); //hide spinner
        })
    }

    //edit invoice details
    onSubmit():void{
        console.log(this.purchaseRequestForm.value);
        console.log('ttttt', this.purchaseRequestForm.value)
        this.spinner.show();//show the spinner
        this.http.put('purchase/update_purchase_req',this.purchaseRequestForm.value,{id:this.id}).subscribe((res:any)=>{ //api for add purchase request details
            console.log(res);
            if(res['message']=='Post successfully')
            {
                this.toastr.success('Data save successfully!', 'SUCCESS!');
            }
            this.spinner.hide(); //hide spinner
            }, err=>{
            this.toastr.error(err.message || err);
            this.spinner.hide(); //hide spinner
        })
    }
}




