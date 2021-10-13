import {IAnswer, IQuestion} from "../types/types";

export class QuestionStore {
    public questions: IQuestion[] = [];

    public addQuestion = (question: IQuestion) => {
        return this.questions.push(question);
    }

    public deleteQuestion = (id: number) => {
        this.questions = this.questions.filter(question => question.id !== id);
    }

    public getQuestionByPosition = (position: number) => {
        return this.questions.find(item => item.position == position)?.questionText
    }
}
