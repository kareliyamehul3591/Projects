import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
@Component({
  selector: 'app-add-expense',
  templateUrl: './add-expense.component.html',
  styleUrls: ['./add-expense.component.css']
})
export class AddExpenseComponent implements OnInit {
  registerform: FormGroup;
  constructor(private formBuilder: FormBuilder,public http:HttpService) { }

  ngOnInit() {
    this.registerform=this.formBuilder.group({
      date:['',Validators.required],
     amount:['',Validators.required],
     type_id:['',Validators.required],
    })
  }

  onSubmit():void
  {
    
    console.log(this.registerform.value);
  }

}
