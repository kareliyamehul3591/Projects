import { Component, OnInit } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
@Component({
  selector: 'app-updategensalary',
  templateUrl: './updategensalary.component.html',
  styleUrls: ['./updategensalary.component.css']
})
export class UpdategensalaryComponent implements OnInit {
  registerform: FormGroup; //form group
  id:any;
  recentdata: any;
  desgn: any;
  first_name: any;
  last_name: any;
  gross_salary: any;
  month: any;
  year: any;
  basic: any;
  hra: any;
  epf: any;
  special: any;
  lta: any;
  conveyance: any;
  cea: any;
  medical: any;
  attire: any;
  other: any;
  pt: any;
  esic: any;
  tds: any;
  advance: any;
  total_deduction: any;
  earning: any;
  deduct: any;
  net_salary: number;
  net: any;

  constructor(public http:HttpService,private formBuilder: FormBuilder,private toastr: ToastrService) { }

  ngOnInit() {
    this.fetch();
    this.buildform();
  }

  //validators for form field
  buildform()
  {
    this.registerform=this.formBuilder.group({
        basic:['',Validators.required],
        hra:['',Validators.required],
        conveyance:['',Validators.required],
        cea:['',Validators.required],
        lta:['',Validators.required],
        medical:['',Validators.required],
        attire:['',Validators.required],
        special:['',Validators.required],
        other:['',Validators.required],
        pt:['',Validators.required],
        esic:['',Validators.required],
        epf:['',Validators.required],
        tds:['',Validators.required],
        advance:['',Validators.required],
        total_deduction:['',Validators.required],
        net_salary:['',Validators.required],
        gross_salary:['',Validators.required]
    })
  }

  fetch()
  {
   this.id=localStorage.getItem('id');
   console.log(this.id);
   this.http.get('salary/Salaryslipview',{id:this.id}).subscribe((res:any)=>{ //api for get salary slip
     console.log(res);
     this.recentdata=res.result;
     this.desgn=this.recentdata[0]['desgn'],
     this.first_name=this.recentdata[0]['first_name'],
     this.last_name=this.recentdata[0]['last_name'],
     this.gross_salary=Math.round(this.recentdata[0]['gross_salary']),
     this.month=this.recentdata[0]['month'],
     this.year=this.recentdata[0]['year'],
     this.basic=Math.round(this.recentdata[0]['basic']);
     this.hra=Math.round(this.recentdata[0]['hra']);
     
     console.log(this.hra);
     this.conveyance=Math.round(this.recentdata[0]['conveyance']);
     this.cea=Math.round(this.recentdata[0]['cea']);
     this.medical=Math.round(this.recentdata[0]['medical']);
     this.attire=Math.round(this.recentdata[0]['attire']);
     
     //this.epf=Math.round(this.gross_salary*0.036);
     this.special=Math.round(this.recentdata[0]['special']);
     this.other=Math.round(this.recentdata[0]['other']);
     this.lta=Math.round(this.recentdata[0]['lta']);

     this.pt=Math.round(this.recentdata[0]['pt']);
     this.esic=Math.round(this.recentdata[0]['esic']);
     this.epf=Math.round(this.recentdata[0]['epf']);
     this.tds=Math.round(this.recentdata[0]['tds']);
     this.advance=Math.round(this.recentdata[0]['advance']);
     this.total_deduction=Math.round(this.recentdata[0]['total_deduction']);
     this.net_salary=Math.round(this.recentdata[0]['net_salary']);
     console.log();
   })
  }
  
  onSubmit():void{
    this.basic=this.registerform.value.basic,
    this.hra=this.registerform.value.hra,
    this.conveyance= this.registerform.value.conveyance,
    this.cea= this.registerform.value.cea,
    this.lta= this.registerform.value.lta,
    this.medical= this.registerform.value.medical,
    this.attire=  this.registerform.value.attire,
    this.special= this.registerform.value.special,
    this.other= this.registerform.value.other,
    this.gross_salary=this.registerform.value.gross_salary,
    //total earning(sum of all earning fields)
    this.earning=(+this.basic+ +this.hra + +this.conveyance + +this.cea+ +this.lta+ +this.medical+ +this.attire+ +this.special+ +this.other);
   
    this.pt= this.registerform.value.pt,
    this.esic= this.registerform.value.esic,
    this.epf= this.registerform.value.epf,
    this.tds= this.registerform.value.tds,
    this.advance= this.registerform.value.advance,
    //total deduction(sum of all deduction field)
    this.deduct=(+this.pt + +this.esic + +this.epf + +this.advance);
    this.total_deduction= this.registerform.value.total_deduction,
    //net salary value
    this.net_salary= this.registerform.value.net_salary,
    //total earning-total deduction
    this.net=(+this.earning - +this.total_deduction);
    console.log(this.deduct);
    console.log(this.total_deduction);
    console.log(this.earning);
    console.log(this.gross_salary);
    console.log(this.net_salary);
    console.log(this.net);
    this.id=localStorage.getItem('id');
    console.log(this.registerform.value);
    const data={
      basic:(this.registerform.value.basic),
      hra:this.registerform.value.hra,
      conveyance:this.registerform.value.conveyance,
      cea:this.registerform.value.cea,
      lta:this.registerform.value.lta,
      medical:this.registerform.value.medical,
      attire:this.registerform.value.attire,
      special:this.registerform.value.special,
      other:this.registerform.value.other,
      gross_salary:this.registerform.value.gross_salary,
      pt:this.registerform.value.pt,
      esic:this.registerform.value.esic,
      epf:this.registerform.value.epf,
      tds:this.registerform.value.tds,
      advance:this.registerform.value.advance,
      total_deduction:this.registerform.value.total_deduction,
      net_salary:this.registerform.value.net_salary
    }
    if(this.earning==this.gross_salary  &&  this.deduct==this.total_deduction && this.net_salary==this.net)
    {
      this.http.put('salary/changeSalarySlip',data,{id:this.id}).subscribe((res:any)=>{ //api for update salary slio
        console.log(res);
        this.toastr.success('Data update', 'success!');
      })
    }
    else{
      this.toastr.warning('Data Is Mismatch!', 'WARNING!');
    }
  
  }
}
