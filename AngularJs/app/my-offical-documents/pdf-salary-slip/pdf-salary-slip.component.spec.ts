import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PdfSalarySlipComponent } from './pdf-salary-slip.component';

describe('PdfSalarySlipComponent', () => {
  let component: PdfSalarySlipComponent;
  let fixture: ComponentFixture<PdfSalarySlipComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PdfSalarySlipComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PdfSalarySlipComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
