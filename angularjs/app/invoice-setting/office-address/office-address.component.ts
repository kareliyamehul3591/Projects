import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { Router } from '@angular/router';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-office-address',
  templateUrl: './office-address.component.html',
  styleUrls: ['./office-address.component.css']
})
export class OfficeAddressComponent implements OnInit {
  addressform: FormGroup; //FORM GROUP
  constructor(private formBuilder: FormBuilder,public http:HttpService,
    private toastr: ToastrService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.buildForm()
    this.showaddress();
  }

  //form validators
  buildForm(){
    this.addressform=this.formBuilder.group({
      name:['',Validators.required],
      address:['',Validators.required]
    })
  }

    //show office address details
    showaddress()
    {
        this.spinner.show();//show the spinner
        this.http.get('invoice/viewaddress',{id:1}).subscribe((res:any)=>{ //API FOR VIEW office address 
            //console.log(res);    
            //console.log(res.result[0].address);
            this.addressform.patchValue({
            name: res.result[0].name,
            address: res.result[0].address,
            })
            this.spinner.hide(); //HIDE SPINNER
        })
    }
    
//update important note
onSubmit():void
{
    console.log(this.addressform.value)
    const data=
    {
        name:this.addressform.value.name,
        address:this.addressform.value.address,
    }
    console.log(data.address)
    this.spinner.show();//show the spinner
    this.http.put('invoice/update-address',data,{id:1}).subscribe((res)=>{ //api for add address in office_add_info
    console.log(res);
    
        if(res['message']=='Updated successfully')
        {
            console.log('save');
            this.toastr.success('Data update successfully!', 'SUCCESS!');
            
        }
        else{
            this.toastr.error('Something wrong!', 'Error!');
            console.log('not');
        }
        this.spinner.hide();//HIDE SPINNER
    })
}

}
