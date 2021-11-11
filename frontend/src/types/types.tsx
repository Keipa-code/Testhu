export interface ITag {
    id: number;
    tagName: string;
}

export interface ITest {
    id?: number;
    testName?: string;
    description?: string;
    rules?: string;
    date?: string;
    timeLimit?: string;
    tags?: ITag[];
    done?: number;
    passed?: number;
    link?: string;
}

export interface IPagination {
    "@id"?: string;
    "hydra:first": string;
    "hydra:last": string;
    "hydra:next": string;
    "hydra:previous": string;
}

export interface IAnswer {
    id: number;
    correct: boolean;
    text: string;
}

export enum AnswerType {
    choose = 'choose',
    number = 'number',
    string = 'string'
}

export interface IQuestion {
    id: number;
    questionText: string;
    position: number;
    points: number;
    answerType: AnswerType;
    answerNumber?: number;
    answerString?: string;
    answers?: IAnswer[];
}
