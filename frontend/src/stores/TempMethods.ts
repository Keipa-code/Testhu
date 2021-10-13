import {IAnswer} from "../types/types";

class temp {
  public addAnswerChoose = (id: number, answerChoose: IAnswer) => {
    this.questions = this.questions.map(question => {
      if (question.id === id) {
        question.answers?.push(answerChoose);
      }
      return question;
    });
  }

  public deleteAnswerChoose = (questionId: number, answerId: number) => {
    this.questions = this.questions.map(question => {
      if (question.id === questionId) {
        question.answers = question.answers?.filter(answer => answer.id !== answerId);
      }
      return question;
    });
  }

  public updateAnswer = (id: number, answerNumber
  ? : number, answerString ? : string
) =>
  {
    this.questions = this.questions.map(question => {
      if (question.id === id) {
        question.answerNumber = answerNumber ?? undefined;
        question.answerString = answerString ?? undefined;
      }
      return question;
    });
  }

  public setQuestionText = (id: number, text: string) => {
    this.questions = this.questions.map(question => {
      if (question.id === id) {
        question.questionText = text;
      }
      return question;
    });
  }

  public getQuestionText = (id: number) => {
    return this.questions.find(item => item.id == id)?.questionText
  }

}