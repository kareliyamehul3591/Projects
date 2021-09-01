import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators, FormArray } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { MatTableDataSource } from '@angular/material/table';

@Component({
  selector: 'app-create-milestones',
  templateUrl: './create-milestones.component.html',
  styleUrls: ['./create-milestones.component.css']
})
export class CreateMilestonesComponent implements OnInit {
  milestoneForm: FormGroup;

  listData:MatTableDataSource<any>; //LISTING DATA IN TABLE
  displayedColumns:string[]=['Milestone Name', 'Description', 'Send Alert', 'Action'];//FEILDS NAME SHOW IN TABLE
  
  constructor(private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService) { }

  ngOnInit() {
    this.buildForm();
  }


  //VALIDATOR FOR FORM'S FIELD
  buildForm(){
    this.milestoneForm=this.formBuilder.group({
      order_type:[''],
      milstoneDetails: this.formBuilder.array([])
    });  
  }

  // create or added milestone details form
  createMilstoneDetailsForm(milstoneDetails?: any): FormGroup {
    let data;
    if (!milstoneDetails) {
      data = this.formBuilder.group({
        milestone_name: [''],
        description: [''],
        send_alert: [''],
      });
    } else {
      data = this.formBuilder.group({
        milestone_name: [milstoneDetails.milestone_name],
        description: [milstoneDetails.description],
        send_alert: [milstoneDetails.send_email]
      });
    }

    return data;
  }

  // add milestone
  addMilstone(): void {
    this.milstoneFormArray.push(this.createMilstoneDetailsForm());
    this.listData = this.milestoneForm.controls['milstoneDetails'].value

  }

  // remove milestone
  removeMilstone(pos: number): void {
    console.log('pos',pos);
    this.milstoneFormArray.removeAt(pos);
    this.listData = this.milestoneForm.controls['milstoneDetails'].value
  }

  //milestone form array
  get milstoneFormArray(): FormArray {
    return this.milestoneForm.get('milstoneDetails') as FormArray;
  }


  //add milestone details
  onSubmit() {
    console.log('ttttt',this.milestoneForm.value)
    this.spinner.show();//show the spinner
    this.http.post('sales/addmilestone',this.milestoneForm.value).subscribe((res:any)=>{ //api for add milestone details
      console.log(res);
      if(res['message']=='Post successfully')
      {
        this.toastr.success('Data save successfully!', 'SUCCESS!');
        this.milestoneForm.reset();
      }
      this.spinner.hide(); //hide spinner
    },
     err=>{
      this.toastr.error(err.message || err);
      this.spinner.hide(); //hide spinner
    }
     )
  }

}
