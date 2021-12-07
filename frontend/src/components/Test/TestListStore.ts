import { IApiResponseCollection, IPagination, ITestList } from '../../types/types';
import $http from '../../utils/http';
import { makeAutoObservable } from 'mobx';

export class TestListStore {
  tests: ITestList[] = [];
  pagination: IPagination;
  totalItems: number;

  constructor() {
    makeAutoObservable(this);
  }

  isEmpty = () => {
    return this.tests.length === 0;
  };

  sliceToNumber = (value) => {
    Object.values(value)
  }

  fetchTests = (urlParams = '') => {
    $http.get('api/tests' + urlParams).then((data: IApiResponseCollection | any) => {
      this.tests = data['hydra:member'];
      this.pagination = data['hydra:view'];
      this.totalItems = data['hydra:totalItems'];
    });
  };
}
