import { IApiResponseCollection, IPagination, ITestList } from '../../types/types';
import $http from '../../utils/http';
import { makeAutoObservable } from 'mobx';
import { paginate } from '../../utils/paginate';

export class TestListStore {
  tests: ITestList[] = [];
  pagination: IPagination = {
    current: '',
    first: '',
    last: '',
    next: '',
    previous: '',
    numbers: [],
  };

  isLoading = false;

  totalPages: number;

  totalItems: number;

  constructor() {
    makeAutoObservable(this);
  }

  isEmpty = () => {
    return this.tests.length === 0;
  };

  computeTotalPages = () => {
    this.totalPages = Math.ceil(this.totalItems / 20);
  };

  setPagination = (value: { [key: string]: string }) => {
    this.pagination.current = value['@id'].slice(-1);
    this.pagination.first = value['hydra:first'].slice(-1);
    this.pagination.last = value['hydra:last'].slice(-1);
    this.pagination.next = value['hydra:next']?.slice(-1) ?? '';
    this.pagination.previous = value['hydra:previous']?.slice(-1) ?? '';
    this.pagination.numbers = paginate(+this.pagination.current, this.totalPages);
  };

  fetchTests = (page, limit) => {
    this.isLoading = true;
    $http
      .get('api/tests', {
        params: {
          page: page,
          itemsPerPage: limit,
        },
      })
      .then((data: IApiResponseCollection | any) => {
        this.tests = data['hydra:member'];
        this.totalItems = data['hydra:totalItems'];
        this.computeTotalPages();
        return data;
      })
      .then((data: any) => {
        this.setPagination(data['hydra:view']);
      })
      .finally(() => (this.isLoading = false));
  };
}
