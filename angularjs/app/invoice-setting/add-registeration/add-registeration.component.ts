import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { Router } from '@angular/router';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-add-registeration',
  templateUrl: './add-registeration.component.html',
  styleUrls: ['./add-registeration.component.css']
})
export class AddRegisterationComponent implements OnInit {
  registerform: FormGroup; //FORM GROUP
  constructor(private formBuilder: FormBuilder,public http:HttpService,
    private toastr: ToastrService,private spinner: NgxSpinnerService) { }

    ngOnInit() {
      this.buildForm();
      this.showregisteration();
    }
  
    //form validators
    buildForm(){
      this.registerform=this.formBuilder.group({
        register:['',Validators.required]
      })
    }

    //show registeration details
  showregisteration()
  {
    this.spinner.show();//show the spinner
    this.http.get('invoice/registeration_view',{id:1}).subscribe((res:any)=>{ //API FOR VIEW registeration_info
    console.log(res);    
    console.log(res.result[0].register);
      this.registerform.patchValue({
        register: res.result[0].register
      })
      this.spinner.hide(); //HIDE SPINNER
    })
  }
  
    //update important note
    onSubmit():void
    {
        console.log(this.registerform.value)
        const data=
        {
            register:this.registerform.value.register
        }
        console.log(data)
        this.spinner.show();//show the spinner
        this.http.put('invoice/registeration-update',data,{id:1}).subscribe((res)=>{ //api for add note in note_info
          console.log(res);
          
          if(res['message']=='Updated successfully')
            {
              console.log('save');
              this.toastr.success('Data update successfully!', 'SUCCESS!');
              //this.registerform.value.register.reset();
              this.spinner.hide();//HIDE SPINNER
            }
          else{
              this.toastr.error('Something wrong!', 'Error!');
              console.log('not');
            }
        })
    }

}
