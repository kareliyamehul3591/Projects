import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CompanyPolicyComponent } from './company-policy.component';

describe('CompanyPolicyComponent', () => {
  let component: CompanyPolicyComponent;
  let fixture: ComponentFixture<CompanyPolicyComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CompanyPolicyComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CompanyPolicyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
