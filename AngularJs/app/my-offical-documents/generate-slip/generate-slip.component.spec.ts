import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { GenerateSlipComponent } from './generate-slip.component';

describe('GenerateSlipComponent', () => {
  let component: GenerateSlipComponent;
  let fixture: ComponentFixture<GenerateSlipComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GenerateSlipComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GenerateSlipComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
