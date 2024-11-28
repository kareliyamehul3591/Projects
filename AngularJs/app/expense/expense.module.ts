import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AddExpenseComponent } from './add-expense/add-expense.component';
import { SlizzingModule } from '../slizzing/slizzing.module';
import { RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';



@NgModule({
  declarations: [AddExpenseComponent],
  imports: [
    CommonModule,
    SlizzingModule,
    RouterModule,
    FormsModule,
    ReactiveFormsModule,
  ],
  exports:[
    AddExpenseComponent
  ]
})
export class ExpenseModule { }
