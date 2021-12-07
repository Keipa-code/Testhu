import { ITest } from "../../types/types";
import $http from '../../utils/http';

export class TestListStore {
  tests: ITest[] = []

  fetchTests = (urlParams:string = '') => {
    $http.get('api/tests' + urlParams)
      .then((data: any) => {
        this.tests = data
      })
  }
}