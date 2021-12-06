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
  tags?: string[];
  done?: number;
  passed?: number;
  isPublic?: boolean;
  isWrongAnswersVisible?: boolean;
  link?: string;
}

export interface IPagination {
  '@id'?: string;
  'hydra:first': string;
  'hydra:last': string;
  'hydra:next': string;
  'hydra:previous': string;
}

export interface IApiResponseCollection {
  '@type': 'hydra:Collection';
  'hydra:member': [];
}
