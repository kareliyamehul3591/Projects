import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { Router } from '@angular/router';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-add-term-cond',
  templateUrl: './add-term-cond.component.html',
  styleUrls: ['./add-term-cond.component.css']
})
export class AddTermCondComponent implements OnInit {

    termform: FormGroup; //FORM GROUP
  
  constructor(private formBuilder: FormBuilder,public http:HttpService,
    private toastr: ToastrService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.buildForm();
  }

  //form validators
  buildForm(){
    this.termform=this.formBuilder.group({
      term:['']
    })
  }

  //add terms note
  onSubmit():void
  {
    console.log(this.termform.value)
    const data=
    {
        term:this.termform.value.term
    }
    console.log(data)
    this.http.post('invoice/add-term',data).subscribe((res)=>{ //api for add term in term_info
        console.log(res);
        this.spinner.show();//show the spinner
        if(res['message']=='Post successfully')
        {
            console.log('save');
            this.toastr.success('Data save successfully!', 'SUCCESS!');
            this.termform.reset();
        }
        else{
            this.toastr.error('Something wrong!', 'Error!');
            console.log('not');
        }
        this.spinner.hide();//HIDE SPINNER
    })
  }


}
