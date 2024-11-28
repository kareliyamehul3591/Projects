import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { Router } from '@angular/router';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from "ngx-spinner";
@Component({
  selector: 'app-important-note',
  templateUrl: './important-note.component.html',
  styleUrls: ['./important-note.component.css']
})
export class ImportantNoteComponent implements OnInit {
  noteform: FormGroup; //FORM GROUP
  showdata:any;
  constructor(private formBuilder: FormBuilder,public http:HttpService,
    private toastr: ToastrService,private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.buildForm();
    this.shownote();
  }

  //form validators
  buildForm(){
    this.noteform=this.formBuilder.group({
      note:['',Validators.required]
    })
  }

  //show note details
  shownote()
  {
    this.spinner.show();//show the spinner
    this.http.get('invoice/viewnote',{id:1}).subscribe((res:any)=>{ //API FOR VIEW note 
    console.log(res);    
    this.showdata=res;
        console.log(res.result[0].note);
        this.noteform.patchValue({
            note: res.result[0].note
       })
      this.spinner.hide(); //HIDE SPINNER
    })
  }
  
  //update important note
  onSubmit():void
  {
    console.log(this.noteform.value)
    const data=
    {
      note:this.noteform.value.note
    }
    console.log(data)
    this.http.put('invoice/updatenote',data,{id:1}).subscribe((res)=>{ //api for add note in note_info
        console.log(res);
        this.spinner.show();//show the spinner
        if(res['message']=='Updated successfully')
        {
            console.log('save');
            this.toastr.success('Data Updated successfully!', 'SUCCESS!');
            //this.noteform.value.note.reset();
        }
        else{
            this.toastr.error('Something wrong!', 'Error!');
            console.log('not');
        }
        this.spinner.hide();//HIDE SPINNER
    })
  }

}
