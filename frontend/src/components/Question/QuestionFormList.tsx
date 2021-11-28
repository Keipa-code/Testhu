import React, {useEffect} from 'react';
import {useRootStore} from "../../RootStateContext";
import QuestionFormItem from "./QuestionFormItem";
import {Button} from "react-bootstrap";
import {observer} from "mobx-react-lite";

const QuestionFormList = observer(() => {
    const { questionFormStore } = useRootStore()

    useEffect(() => {
        questionFormStore.getFromStorage('newQuestions')
        const interval = setInterval(() => questionFormStore.setToStorage('newQuestions'), 360000)
        return () => clearInterval(interval)
    }, [])

    const handleClick = () => {
        questionFormStore.addQuestion()
    }

    return (
        <div>
            <h2 className="mb-3">Вопросы теста</h2>
            {questionFormStore.questions.map((question, qKey) =>
                <QuestionFormItem
                    key={qKey}
                    qKey={qKey}
                    question={question}
                    inputChange={questionFormStore.inputChange}
                />
            )}
            <Button className="mb-3" onClick={handleClick}>Добавить вопрос</Button>
        </div>
    );
});

export default QuestionFormList;
