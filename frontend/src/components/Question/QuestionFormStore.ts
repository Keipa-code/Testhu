import { makeAutoObservable, runInAction } from 'mobx';
import $http from '../../utils/http';
import { storage } from '../../utils/tools';
import isNumeric from 'antd/es/_util/isNumeric';

export interface IAnswer {
  correct: boolean;
  text: string;
}

export interface IQuestion {
  id?: number;
  questionText?: string;
  position?: number;
  points?: number;
  answerType?: string;
  answerInput?: number | string;
  answers?: IAnswer[];
  test?: string;
}

export class QuestionFormStore {
  questions: IQuestion[];

  constructor() {
    this.questions = [];
    makeAutoObservable(this);
  }

  inputChange = (qKey: number, value: string | number, type: string, aKey?: number) => {
    if (!aKey) {
      this.questions[qKey][type] = isNumeric(value) ? Number(value) : value;
    } else {
      this.questions[qKey].answers[aKey][type] = isNumeric(value) ? Number(value) : value;
    }
  };

  addQuestion = () => {
    this.questions.push({ points: 50, answers: [] });
  };

  removeQuestion = (index: number) => {
    this.questions.splice(index, 1);
  };

  addAnswer = (qKey: number, value: string) => {
    this.questions[qKey].answers.push({ correct: false, text: value });
  };

  answerCheckedChange = (qKey: number, answerIndex: number) => {
    runInAction(() => {
      this.questions[qKey].answers[answerIndex].correct = !this.questions[qKey].answers[answerIndex].correct;
    });
  };

  removeAnswer = (qKey: number, answerIndex: number): void => {
    this.questions[qKey].answers.splice(answerIndex, 1);
  };

  answersIsEmpty = (qKey: number): boolean => {
    return this.questions[qKey].answers.length !== 0;
  };

  fetchItem = (id: string) => {
    $http.get<IQuestion>('/api/questions/' + id).then((res: any) => {
      const data: IQuestion = res;
      runInAction(() => {
        this.questions.push(data);
      });
    });
  };

  fetchCollection = (id: string) => {
    $http.get<IQuestion[]>('/api/questions?test.id=' + id).then((res: any) => {
      const data: IQuestion[] = res;
      runInAction(() => {
        this.questions = data;
      });
    });
  };

  postQuestions = (testID: string | number) => {
    this.questions.map((question) => {
      question.test = '/api/tests/' + testID;
      $http.post<IQuestion[]>('/api/questions', question);
    });
  };

  getFromStorage = (key: string) => {
    if (storage.get(key)) {
      this.questions = storage.get(key);
    }
  };

  setToStorage = (key: string) => {
    runInAction(() => {
      storage.remove('key');
      storage.set(key, this.questions);
    });
  };
}
