import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateMilestonesComponent } from './create-milestones.component';

describe('CreateMilestonesComponent', () => {
  let component: CreateMilestonesComponent;
  let fixture: ComponentFixture<CreateMilestonesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CreateMilestonesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CreateMilestonesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
