import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-upload-logo',
  templateUrl: './upload-logo.component.html',
  styleUrls: ['./upload-logo.component.css']
})
export class UploadLogoComponent implements OnInit {
  logoform: FormGroup; //form group

  constructor(private formBuilder: FormBuilder,public http:HttpService,
    private toastr: ToastrService,private spinner: NgxSpinnerService) { }
    formData=new FormData();

  ngOnInit() {
    this.buildForm();
    this.showlogo();
  }

  //form validators
  buildForm(){
    this.logoform=this.formBuilder.group({
      logo:['']
    })
  }

  onSelectedFile(event)
  {
    console.log(event.target.files);
    if(event.target.files){
    const formData=new FormData();
    this.formData.append('logo_form', event.target.files[0]);
    }
  }

  //show registeration details
  showlogo()
  {
    this.spinner.show();//show the spinner
    this.http.get('invoice/logo-view',{id:1}).subscribe((res:any)=>{ //API FOR VIEW registeration_info
    console.log(res);    
    
      this.logvalue=res.result[0].logo
      console.log(this.logvalue);
      this.spinner.hide(); //HIDE SPINNER
    })
  }

  logvalue(logvalue: any) {
    throw new Error("Method not implemented.");
  }

  //upload logo
  onSubmit():void
  {
    console.log(this.logoform.value);
    this.formData.append('logo',this.logoform.get('logo').value);
    this.spinner.show();//show the spinner
    this.http.put('invoice/logo-change',this.formData,{id:1}).subscribe((res:any)=>{ //api for add logo of company
        console.log('hi')
        console.log(res);
    if(res['message']=='Updated successfully')
    {
      this.toastr.success('Data update Successfully','Success!');
      this.ngOnInit();
      this.logoform.reset();
      this.formData = new FormData();
    }
    this.spinner.hide(); //hide spinner
    }, err=>{
      this.toastr.error(err.message || err);
    })
  }
}
