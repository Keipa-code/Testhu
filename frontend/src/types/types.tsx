export interface ITag {
    id: number;
    tagName: string;
}

export interface ITest {
    id: number;
    testName: string;
    tags: ITag[];
    done: number;
    passed: number;
    link?: string;
}