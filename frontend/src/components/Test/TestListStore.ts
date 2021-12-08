import { IApiResponseCollection, IPagination, ITestList } from '../../types/types';
import $http from '../../utils/http';
import { makeAutoObservable } from 'mobx';

export class TestListStore {
  tests: ITestList[] = [];
  pagination: IPagination = {
    current: '',
    first: '',
    last: '',
    next: '',
    previous: '',
  };

  totalItems: number;

  constructor() {
    makeAutoObservable(this);
  }

  isEmpty = () => {
    return this.tests.length === 0;
  };

  totalPages = () => {
    return Math.ceil(this.totalItems / 20);
  };

  setPagination = (value: { [key: string]: string }) => {
    this.pagination.current = value['@id'].slice(-1);
    this.pagination.first = value['hydra:first'].slice(10);
    this.pagination.last = value['hydra:last'].slice(10);
    this.pagination.next = value['hydra:next']?.slice(10) ?? '';
    this.pagination.previous = value['hydra:previous']?.slice(10) ?? '';
  };

  fetchTests = (urlParams = '') => {
    $http.get('api/tests' + urlParams).then((data: IApiResponseCollection | any) => {
      this.tests = data['hydra:member'];
      this.setPagination(data['hydra:view']);
      this.totalItems = data['hydra:totalItems'];
    });
  };
}
