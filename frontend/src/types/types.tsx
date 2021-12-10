export interface ITag {
  id: number;
  tagName: string;
}

export interface ITimeLimit {
  hour?: string;
  minute?: string;
}

export interface ITest {
  id?: number;
  testName?: string;
  description?: string;
  rules?: string;
  date?: Date;
  timeLimit?: ITimeLimit;
  done?: number;
  passed?: number;
  isPublic?: boolean;
  isWrongAnswersVisible?: boolean;
  link?: string;
}

export interface ITestForm extends ITest {
  tags?: string[];
}

export interface ITestList extends ITest {
  tags?: ITag[];
}

export interface IPagination {
  current: string;
  first: string;
  last: string;
  next?: string;
  previous?: string;
  numbers: number[];
}

export interface IApiResponseCollection {
  'hydra:member': ITestList[];
  'hydra:totalItems': number;
  'hydra:view': IPagination[];
  // 'hydra:search':
}
